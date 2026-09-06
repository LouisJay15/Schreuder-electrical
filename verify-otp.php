<?php
require_once __DIR__ . '/backend/bootstrap.php';

$pendingId = $_SESSION['pending_2fa_user_id'] ?? null;
if (!$pendingId || ($_SESSION['pending_2fa_expires'] ?? 0) < time()) {
    unset($_SESSION['pending_2fa_user_id'], $_SESSION['pending_2fa_expires'], $_SESSION['pending_2fa_next'], $_SESSION['pending_2fa_is_registration']);
    header('Location: /login');
    exit;
}

$pdo = aloe_db();
$stmt = $pdo->prepare('SELECT id, full_name, email, role FROM users WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $pendingId]);
$pendingUser = $stmt->fetch();
if (!$pendingUser) {
    header('Location: /login');
    exit;
}

$error = null;
$notice = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();
    $ip = aloe_client_ip();

    if (isset($_POST['resend'])) {
        if (!rate_limit_allow($pdo, 'otp_resend', (string) $pendingId, ...rate_limit_policy('otp_resend'))) {
            $error = 'You have requested too many codes. Please wait a few minutes.';
        } else {
            $code = issue_login_otp((int) $pendingId, 'login_2fa');
            aloe_send_otp_email($pendingUser['email'], $pendingUser['full_name'], $code);
            $_SESSION['pending_2fa_expires'] = time() + 600;
            $notice = 'We sent a new code to ' . $pendingUser['email'] . '.';
        }
    } else {
        $code = clean_str($_POST['code'] ?? '', 6);
        if (!rate_limit_allow($pdo, 'otp_verify', (string) $pendingId . '|' . $ip, ...rate_limit_policy('otp_verify'))) {
            $error = 'Too many attempts. Please request a new code shortly.';
        } else {
            $result = verify_login_otp((int) $pendingId, $code, 'login_2fa');
            if ($result === 'ok') {
                if (!empty($_SESSION['pending_2fa_is_registration'])) {
                    $pdo->prepare('UPDATE users SET email_verified_at = NOW() WHERE id = :id')->execute(['id' => $pendingId]);
                }
                $next = $_SESSION['pending_2fa_next'] ?? ($pendingUser['role'] === 'admin' ? '/admin/' : '/dashboard');
                login_user($pendingUser);
                log_event((int) $pendingId, 'login_success');
                header('Location: ' . $next);
                exit;
            }
            $error = match ($result) {
                'expired' => 'That code has expired. Request a new one below.',
                'too_many_attempts' => 'Too many incorrect attempts. Request a new code.',
                default => 'That code is incorrect. Please try again.',
            };
        }
    }
}

$pageTitle = 'Verify your email';
$canonicalPath = '/verify-otp';
$noindex = true;
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section" style="max-width:440px; margin:0 auto;">
    <div class="container">
      <p class="eyebrow">Two-step verification</p>
      <h1>Enter your code</h1>
      <p class="lede">We emailed a 6-digit code to <strong><?= e($pendingUser['email']) ?></strong>. It expires in 10 minutes.</p>

      <div class="form-card" style="margin-top:24px;">
        <?php if ($error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
        <?php if ($notice): ?><div class="alert alert-success"><?= e($notice) ?></div><?php endif; ?>
        <form method="post" action="/verify-otp" novalidate>
          <?= csrf_field() ?>
          <div class="field">
            <label for="code">Verification code</label>
            <input type="text" id="code" name="code" inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code" required autofocus style="letter-spacing:0.4em; font-size:1.3rem; text-align:center;">
          </div>
          <button type="submit" class="btn btn-primary btn-block">Verify and continue</button>
        </form>
        <form method="post" action="/verify-otp" style="margin-top:14px; text-align:center;">
          <?= csrf_field() ?>
          <button type="submit" name="resend" value="1" class="btn btn-secondary btn-block">Resend code</button>
        </form>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
