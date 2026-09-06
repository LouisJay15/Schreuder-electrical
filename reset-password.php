<?php
require_once __DIR__ . '/backend/bootstrap.php';

$pdo = aloe_db();
$token = clean_str($_GET['token'] ?? $_POST['token'] ?? '', 64);
$error = null;
$done = false;
$validToken = false;
$resetRow = null;

if ($token !== '') {
    $stmt = $pdo->prepare(
        'SELECT pr.id, pr.user_id, pr.expires_at, u.full_name, u.email
         FROM password_resets pr JOIN users u ON u.id = pr.user_id
         WHERE pr.token_hash = :hash AND pr.consumed_at IS NULL LIMIT 1'
    );
    $stmt->execute(['hash' => hash('sha256', $token)]);
    $resetRow = $stmt->fetch();
    $validToken = $resetRow && strtotime($resetRow['expires_at']) > time();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $validToken) {
    csrf_require();
    $ip = aloe_client_ip();
    if (!rate_limit_allow($pdo, 'password_reset_confirm', $ip, ...rate_limit_policy('password_reset_confirm'))) {
        $error = 'Too many attempts. Please wait a while and try again.';
    } else {
        $password = (string) ($_POST['password'] ?? '');
        $confirm = (string) ($_POST['confirm_password'] ?? '');
        $pwErrors = password_policy_errors($password, $resetRow['email'], $resetRow['full_name']);

        if ($password !== $confirm) {
            $error = 'Passwords do not match.';
        } elseif ($pwErrors) {
            $error = implode(' ', $pwErrors);
        } elseif (is_password_pwned($password)) {
            $error = 'That password has appeared in a public data breach. Please choose a different one.';
        } else {
            $pdo->prepare('UPDATE users SET password_hash = :h WHERE id = :id')
                ->execute(['h' => password_hash($password, PASSWORD_DEFAULT), 'id' => $resetRow['user_id']]);
            $pdo->prepare('UPDATE password_resets SET consumed_at = NOW() WHERE user_id = :uid AND consumed_at IS NULL')
                ->execute(['uid' => $resetRow['user_id']]);
            log_event((int) $resetRow['user_id'], 'password_reset_completed');
            $done = true;
        }
    }
}

$pageTitle = 'Choose a new password';
$canonicalPath = '/reset-password';
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
      <h1>Choose a new password</h1>

      <div class="form-card" style="margin-top:24px;">
        <?php if ($done): ?>
          <div class="alert alert-success">Your password has been updated.</div>
          <a href="/login" class="btn btn-primary btn-block">Log in</a>
        <?php elseif (!$validToken): ?>
          <div class="alert alert-error">This link is invalid or has expired.</div>
          <a href="/forgot-password" class="btn btn-primary btn-block">Request a new link</a>
        <?php else: ?>
          <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
          <form method="post" action="/reset-password" novalidate>
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= e($token) ?>">
            <div class="field">
              <label for="password">New password</label>
              <input type="password" id="password" name="password" required autocomplete="new-password" autofocus>
              <span class="hint">At least 12 characters, with upper, lower, number and symbol.</span>
            </div>
            <div class="field">
              <label for="confirm_password">Confirm new password</label>
              <input type="password" id="confirm_password" name="confirm_password" required autocomplete="new-password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Update password</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
