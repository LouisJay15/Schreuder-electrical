<?php
require_once __DIR__ . '/backend/bootstrap.php';

if (current_user()) {
    header('Location: /dashboard');
    exit;
}

$errors = [];
$email = '';
$next = filter_var($_GET['next'] ?? $_POST['next'] ?? '/dashboard', FILTER_DEFAULT);
if (!str_starts_with($next, '/') || str_starts_with($next, '//')) {
    $next = '/dashboard'; // never redirect off-site
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();

    $email = strtolower(clean_str($_POST['email'] ?? '', 190));
    $password = (string) ($_POST['password'] ?? '');
    $ip = aloe_client_ip();
    $pdo = aloe_db();

    $accountKey = $ip . '|' . $email;
    $accountOk = rate_limit_allow($pdo, 'login_account', $accountKey, ...rate_limit_policy('login_account'));
    $ipOk = rate_limit_allow($pdo, 'login_ip', $ip, ...rate_limit_policy('login_ip'));

    if (!$accountOk || !$ipOk) {
        rate_limit_reject();
    }

    // Deliberately generic error: never reveal whether the email exists.
    $genericError = 'Incorrect email or password.';

    if (!valid_email($email) || $password === '') {
        $errors['form'] = $genericError;
    } else {
        $stmt = $pdo->prepare('SELECT id, full_name, email, password_hash, role, status FROM users WHERE email = :e LIMIT 1');
        $stmt->execute(['e' => $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $errors['form'] = $genericError;
            log_event($user['id'] ?? null, 'login_failed', $email);
        } elseif ($user['status'] !== 'active') {
            $errors['form'] = 'This account is locked. Contact support for help.';
        } else {
            $code = issue_login_otp((int) $user['id'], 'login_2fa');
            aloe_send_otp_email($user['email'], $user['full_name'], $code);
            $_SESSION['pending_2fa_user_id'] = (int) $user['id'];
            $_SESSION['pending_2fa_expires'] = time() + 600;
            $_SESSION['pending_2fa_next'] = $next;
            log_event((int) $user['id'], 'login_password_ok');
            header('Location: /verify-otp');
            exit;
        }
    }
}

$pageTitle = 'Log in';
$pageDescription = 'Log in to your Aloe Credit account.';
$canonicalPath = '/login';
$noindex = true;
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section" style="max-width:460px; margin:0 auto;">
    <div class="container">
      <p class="eyebrow">Welcome back</p>
      <h1>Log in</h1>
      <p class="lede">We'll send a one-time code to your email to confirm it's you.</p>

      <div class="form-card" style="margin-top:24px;">
        <?php if (isset($errors['form'])): ?>
          <div class="alert alert-error"><?= e($errors['form']) ?></div>
        <?php endif; ?>
        <form method="post" action="/login" novalidate>
          <?= csrf_field() ?>
          <input type="hidden" name="next" value="<?= e($next) ?>">
          <div class="field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" value="<?= e($email) ?>" required autofocus>
          </div>
          <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required autocomplete="current-password">
          </div>
          <button type="submit" class="btn btn-primary btn-block">Continue</button>
        </form>
      </div>
      <p style="margin-top:20px; text-align:center;" class="muted">
        <a href="/forgot-password">Forgot your password?</a> ·
        New here? <a href="/register">Create an account</a>
      </p>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
