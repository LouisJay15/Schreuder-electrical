<?php
require_once __DIR__ . '/backend/bootstrap.php';
$user = require_login();
$pdo = aloe_db();

$pwError = null;
$pwSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    csrf_require();
    $current = (string) ($_POST['current_password'] ?? '');
    $new = (string) ($_POST['new_password'] ?? '');
    $confirm = (string) ($_POST['confirm_new_password'] ?? '');

    $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = :id');
    $stmt->execute(['id' => $user['id']]);
    $hash = $stmt->fetchColumn();

    if (!password_verify($current, $hash)) {
        $pwError = 'Your current password is incorrect.';
    } elseif ($new !== $confirm) {
        $pwError = 'New passwords do not match.';
    } else {
        $pwErrors = password_policy_errors($new, $user['email'], $user['full_name']);
        if ($pwErrors) {
            $pwError = implode(' ', $pwErrors);
        } elseif (is_password_pwned($new)) {
            $pwError = 'That password has appeared in a public data breach. Please choose a different one.';
        } else {
            $pdo->prepare('UPDATE users SET password_hash = :h WHERE id = :id')
                ->execute(['h' => password_hash($new, PASSWORD_DEFAULT), 'id' => $user['id']]);
            log_event((int) $user['id'], 'password_changed');
            $pwSuccess = true;
        }
    }
}

$apps = $pdo->prepare('SELECT id, loan_amount, loan_term_months, status, created_at FROM applications WHERE user_id = :id ORDER BY created_at DESC');
$apps->execute(['id' => $user['id']]);
$applications = $apps->fetchAll();

$statusLabel = ['new' => 'Submitted', 'reviewing' => 'In review', 'approved' => 'Approved', 'declined' => 'Declined'];

$pageTitle = 'My account';
$canonicalPath = '/dashboard';
$noindex = true;
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section">
    <div class="container">
      <p class="eyebrow">My account</p>
      <h1>Welcome back, <?= e(explode(' ', $user['full_name'])[0]) ?>.</h1>

      <div class="grid grid-2" style="margin-top:32px; align-items:start;">
        <div class="card">
          <h3>Your applications</h3>
          <?php if (!$applications): ?>
            <p class="muted">You haven't submitted a loan application yet.</p>
            <a href="/apply" class="btn btn-primary">Apply now</a>
          <?php else: ?>
            <div class="table-wrap" style="margin-top:12px;">
              <table>
                <thead><tr><th>Date</th><th>Amount</th><th>Term</th><th>Status</th></tr></thead>
                <tbody>
                <?php foreach ($applications as $a): ?>
                  <tr>
                    <td><?= e(date('d M Y', strtotime($a['created_at']))) ?></td>
                    <td>R<?= number_format((float) $a['loan_amount'], 0) ?></td>
                    <td><?= (int) $a['loan_term_months'] ?> months</td>
                    <td><span class="badge"><?= e($statusLabel[$a['status']] ?? $a['status']) ?></span></td>
                  </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

        <div class="card">
          <h3>Change password</h3>
          <?php if ($pwSuccess): ?><div class="alert alert-success">Password updated.</div><?php endif; ?>
          <?php if ($pwError): ?><div class="alert alert-error"><?= e($pwError) ?></div><?php endif; ?>
          <form method="post" action="/dashboard" novalidate>
            <?= csrf_field() ?>
            <div class="field">
              <label for="current_password">Current password</label>
              <input type="password" id="current_password" name="current_password" required autocomplete="current-password">
            </div>
            <div class="field">
              <label for="new_password">New password</label>
              <input type="password" id="new_password" name="new_password" required autocomplete="new-password">
              <span class="hint">At least 12 characters, with upper, lower, number and symbol.</span>
            </div>
            <div class="field">
              <label for="confirm_new_password">Confirm new password</label>
              <input type="password" id="confirm_new_password" name="confirm_new_password" required autocomplete="new-password">
            </div>
            <button type="submit" name="change_password" value="1" class="btn btn-primary btn-block">Update password</button>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
