<?php
require_once __DIR__ . '/backend/bootstrap.php';

if (current_user()) {
    header('Location: /dashboard');
    exit;
}

$errors = [];
$fullName = $email = $phone = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();

    if (!rate_limit_allow(aloe_db(), 'register', aloe_client_ip(), ...rate_limit_policy('register'))) {
        rate_limit_reject();
    }

    $fullName = clean_str($_POST['full_name'] ?? '', 150);
    $email = strtolower(clean_str($_POST['email'] ?? '', 190));
    $phone = clean_str($_POST['phone'] ?? '', 30);
    $password = (string) ($_POST['password'] ?? '');
    $confirm = (string) ($_POST['confirm_password'] ?? '');
    $agreed = isset($_POST['agree_terms']);

    if ($fullName === '' || mb_strlen($fullName) < 2) {
        $errors['full_name'] = 'Enter your full name.';
    }
    if (!valid_email($email)) {
        $errors['email'] = 'Enter a valid email address.';
    }
    if (!valid_phone($phone)) {
        $errors['phone'] = 'Enter a valid phone number.';
    }
    if ($password !== $confirm) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }
    $pwErrors = password_policy_errors($password, $email, $fullName);
    if ($pwErrors) {
        $errors['password'] = implode(' ', $pwErrors);
    } elseif (is_password_pwned($password)) {
        $errors['password'] = 'That password has appeared in a public data breach. Please choose a different one.';
    }
    if (!$agreed) {
        $errors['agree_terms'] = 'You must accept the Terms and Privacy Policy to continue.';
    }

    if (!$errors) {
        $pdo = aloe_db();
        $exists = $pdo->prepare('SELECT id FROM users WHERE email = :e LIMIT 1');
        $exists->execute(['e' => $email]);
        if ($exists->fetch()) {
            $errors['email'] = 'An account with that email already exists.';
        }
    }

    if (!$errors) {
        $pdo = aloe_db();
        $stmt = $pdo->prepare(
            'INSERT INTO users (full_name, email, phone, password_hash, role, status, created_at)
             VALUES (:name, :email, :phone, :hash, "client", "active", NOW())'
        );
        $stmt->execute([
            'name'  => $fullName,
            'email' => $email,
            'phone' => $phone,
            'hash'  => password_hash($password, PASSWORD_DEFAULT),
        ]);
        $userId = (int) $pdo->lastInsertId();
        log_event($userId, 'register', $email);

        $code = issue_login_otp($userId, 'login_2fa');
        aloe_send_otp_email($email, $fullName, $code);

        $_SESSION['pending_2fa_user_id'] = $userId;
        $_SESSION['pending_2fa_expires'] = time() + 600;
        $_SESSION['pending_2fa_is_registration'] = true;
        header('Location: /verify-otp');
        exit;
    }
}

$pageTitle = 'Create your account';
$pageDescription = 'Create a free Aloe Credit account to apply for a loan and track your application.';
$canonicalPath = '/register';
$noindex = true;
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section" style="max-width:520px; margin:0 auto;">
    <div class="container">
      <p class="eyebrow">Get started</p>
      <h1>Create your account</h1>
      <p class="lede">Takes about a minute. We'll email you a one-time code to verify it's really you.</p>

      <div class="form-card" style="margin-top:24px;">
        <?php if ($errors): ?>
          <div class="alert alert-error">Please fix the highlighted fields below.</div>
        <?php endif; ?>
        <form method="post" action="/register" novalidate>
          <?= csrf_field() ?>
          <div class="field">
            <label for="full_name">Full name</label>
            <input type="text" id="full_name" name="full_name" value="<?= e($fullName) ?>" class="<?= isset($errors['full_name']) ? 'has-error' : '' ?>" required>
            <?php if (isset($errors['full_name'])): ?><span class="error"><?= e($errors['full_name']) ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label for="email">Email address</label>
            <input type="email" id="email" name="email" value="<?= e($email) ?>" class="<?= isset($errors['email']) ? 'has-error' : '' ?>" required>
            <?php if (isset($errors['email'])): ?><span class="error"><?= e($errors['email']) ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label for="phone">Phone number</label>
            <input type="tel" id="phone" name="phone" value="<?= e($phone) ?>" class="<?= isset($errors['phone']) ? 'has-error' : '' ?>" required>
            <?php if (isset($errors['phone'])): ?><span class="error"><?= e($errors['phone']) ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="<?= isset($errors['password']) ? 'has-error' : '' ?>" required autocomplete="new-password">
            <span class="hint">At least 12 characters, with upper, lower, number and symbol.</span>
            <?php if (isset($errors['password'])): ?><span class="error"><?= e($errors['password']) ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label for="confirm_password">Confirm password</label>
            <input type="password" id="confirm_password" name="confirm_password" class="<?= isset($errors['confirm_password']) ? 'has-error' : '' ?>" required autocomplete="new-password">
            <?php if (isset($errors['confirm_password'])): ?><span class="error"><?= e($errors['confirm_password']) ?></span><?php endif; ?>
          </div>
          <label class="checkbox-row" style="margin-bottom:20px;">
            <input type="checkbox" name="agree_terms" <?= isset($_POST['agree_terms']) ? 'checked' : '' ?>>
            <span>I agree to the <a href="/terms">Terms of Use</a> and <a href="/privacy-policy">Privacy Policy</a>.</span>
          </label>
          <?php if (isset($errors['agree_terms'])): ?><p class="error" style="margin-top:-12px;"><?= e($errors['agree_terms']) ?></p><?php endif; ?>
          <button type="submit" class="btn btn-primary btn-block">Create account</button>
        </form>
      </div>
      <p style="margin-top:20px; text-align:center;" class="muted">Already have an account? <a href="/login">Log in</a></p>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
