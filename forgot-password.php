<?php
require_once __DIR__ . '/backend/bootstrap.php';

if (current_user()) {
    header('Location: /dashboard');
    exit;
}

$sent = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();
    $email = strtolower(clean_str($_POST['email'] ?? '', 190));
    $ip = aloe_client_ip();
    $pdo = aloe_db();

    $accountOk = rate_limit_allow($pdo, 'password_reset_account', $ip . '|' . $email, ...rate_limit_policy('password_reset_account'));
    $ipOk = rate_limit_allow($pdo, 'password_reset_ip', $ip, ...rate_limit_policy('password_reset_ip'));

    if (!$accountOk || !$ipOk) {
        $error = 'Too many reset requests. Please wait a while and try again.';
    } elseif (!valid_email($email)) {
        $error = 'Enter a valid email address.';
    } else {
        $stmt = $pdo->prepare('SELECT id, full_name, email FROM users WHERE email = :e AND status = "active" LIMIT 1');
        $stmt->execute(['e' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            $pdo->prepare('UPDATE password_resets SET consumed_at = NOW() WHERE user_id = :uid AND consumed_at IS NULL')
                ->execute(['uid' => $user['id']]);

            $token = bin2hex(random_bytes(32));
            $pdo->prepare(
                'INSERT INTO password_resets (user_id, token_hash, expires_at, created_at)
                 VALUES (:uid, :hash, DATE_ADD(NOW(), INTERVAL 30 MINUTE), NOW())'
            )->execute(['uid' => $user['id'], 'hash' => hash('sha256', $token)]);

            $config = require __DIR__ . '/backend/config.php';
            $resetUrl = $config['app']['url'] . '/reset-password?token=' . $token;
            aloe_send_reset_email($user['email'], $user['full_name'], $resetUrl);
            log_event((int) $user['id'], 'password_reset_requested');
        }
        // Same response whether or not the account exists — no enumeration.
        $sent = true;
    }
}

$pageTitle = 'Forgot password';
$canonicalPath = '/forgot-password';
$noindex = true;
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section" style="max-width:460px; margin:0 auto;">
    <div class="container">
      <p class="eyebrow">Account recovery</p>
      <h1>Reset your password</h1>
      <p class="lede">Enter the email on your account and we'll send you a reset link.</p>

      <div class="form-card" style="margin-top:24px;">
        <?php if ($sent): ?>
          <div class="alert alert-success">If an account exists for that email, a reset link is on its way. Check your inbox (and spam folder).</div>
        <?php else: ?>
          <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
          <form method="post" action="/forgot-password" novalidate>
            <?= csrf_field() ?>
            <div class="field">
              <label for="email">Email address</label>
              <input type="email" id="email" name="email" required autofocus>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Send reset link</button>
          </form>
        <?php endif; ?>
      </div>
      <p style="margin-top:20px; text-align:center;" class="muted"><a href="/login">Back to login</a></p>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
