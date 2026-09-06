<?php
require_once __DIR__ . '/../backend/bootstrap.php';
$admin = require_admin();
$pdo = aloe_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mark_handled'])) {
    csrf_require();
    $id = (int) ($_POST['message_id'] ?? 0);
    $pdo->prepare('UPDATE contact_messages SET handled = 1 WHERE id = :id')->execute(['id' => $id]);
    log_event((int) $admin['id'], 'admin_message_handled', "msg#$id");
    header('Location: /admin/messages');
    exit;
}

$messages = $pdo->query('SELECT * FROM contact_messages ORDER BY handled ASC, created_at DESC LIMIT 100')->fetchAll();

$pageTitle = 'Contact messages';
$canonicalPath = '/admin/messages';
$noindex = true;
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/../partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>
<main id="main">
  <section class="section">
    <div class="container">
      <p class="eyebrow">Admin</p>
      <h1>Contact messages</h1>
      <div class="grid" style="margin-top:24px; gap:16px;">
        <?php foreach ($messages as $m): ?>
          <div class="card" style="opacity:<?= $m['handled'] ? '0.6' : '1' ?>;">
            <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap;">
              <div>
                <strong><?= e($m['name']) ?></strong> — <?= e($m['email']) ?><?php if ($m['phone']): ?> · <?= e($m['phone']) ?><?php endif; ?>
                <p class="muted" style="margin:4px 0 0;"><?= e(date('d M Y H:i', strtotime($m['created_at']))) ?></p>
              </div>
              <?php if (!$m['handled']): ?>
                <form method="post" action="/admin/messages">
                  <?= csrf_field() ?>
                  <input type="hidden" name="message_id" value="<?= (int) $m['id'] ?>">
                  <button type="submit" name="mark_handled" value="1" class="btn btn-secondary">Mark handled</button>
                </form>
              <?php else: ?>
                <span class="badge">Handled</span>
              <?php endif; ?>
            </div>
            <?php if ($m['subject']): ?><p style="margin-top:12px;"><strong><?= e($m['subject']) ?></strong></p><?php endif; ?>
            <p style="margin-top:8px; white-space:pre-wrap;"><?= e($m['message']) ?></p>
          </div>
        <?php endforeach; ?>
        <?php if (!$messages): ?><p class="muted">No messages yet.</p><?php endif; ?>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
