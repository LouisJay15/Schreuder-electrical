<?php
require_once __DIR__ . '/../backend/bootstrap.php';
$admin = require_admin(); // server-side role check, re-verified against the DB on every request

$pdo = aloe_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    csrf_require();
    $appId = (int) ($_POST['application_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    if (in_array($status, ['new', 'reviewing', 'approved', 'declined'], true) && $appId > 0) {
        $pdo->prepare('UPDATE applications SET status = :s WHERE id = :id')->execute(['s' => $status, 'id' => $appId]);
        log_event((int) $admin['id'], 'admin_application_status_changed', "app#$appId -> $status");
    }
    header('Location: /admin/');
    exit;
}

$counts = $pdo->query('SELECT status, COUNT(*) c FROM applications GROUP BY status')->fetchAll(PDO::FETCH_KEY_PAIR);
$totalUsers = (int) $pdo->query('SELECT COUNT(*) FROM users WHERE role = "client"')->fetchColumn();
$newMessages = (int) $pdo->query('SELECT COUNT(*) FROM contact_messages WHERE handled = 0')->fetchColumn();

$apps = $pdo->query(
    'SELECT a.id, a.full_name, a.email, a.phone, a.loan_amount, a.loan_term_months, a.employment_status, a.status, a.created_at
     FROM applications a ORDER BY a.created_at DESC LIMIT 50'
)->fetchAll();

$pageTitle = 'Admin dashboard';
$canonicalPath = '/admin/';
$noindex = true;
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/../partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>
<main id="main">
  <section class="section">
    <div class="container">
      <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
        <div>
          <p class="eyebrow">Admin</p>
          <h1>Applications overview</h1>
        </div>
        <a href="/admin/messages" class="btn btn-secondary">
          Contact messages <?php if ($newMessages): ?><span class="badge"><?= $newMessages ?> new</span><?php endif; ?>
        </a>
      </div>

      <div class="grid grid-4" style="margin:32px 0;">
        <div class="card"><span class="muted">New</span><h3 style="margin-top:6px;"><?= (int) ($counts['new'] ?? 0) ?></h3></div>
        <div class="card"><span class="muted">Reviewing</span><h3 style="margin-top:6px;"><?= (int) ($counts['reviewing'] ?? 0) ?></h3></div>
        <div class="card"><span class="muted">Approved</span><h3 style="margin-top:6px;"><?= (int) ($counts['approved'] ?? 0) ?></h3></div>
        <div class="card"><span class="muted">Registered clients</span><h3 style="margin-top:6px;"><?= $totalUsers ?></h3></div>
      </div>

      <div class="table-wrap">
        <table>
          <thead>
            <tr><th>Applicant</th><th>Contact</th><th>Amount</th><th>Term</th><th>Employment</th><th>Received</th><th>Status</th></tr>
          </thead>
          <tbody>
          <?php foreach ($apps as $a): ?>
            <tr>
              <td><?= e($a['full_name']) ?></td>
              <td><?= e($a['email']) ?><br><span class="muted"><?= e($a['phone']) ?></span></td>
              <td>R<?= number_format((float) $a['loan_amount'], 0) ?></td>
              <td><?= (int) $a['loan_term_months'] ?> mo</td>
              <td><?= e($a['employment_status']) ?></td>
              <td><?= e(date('d M Y', strtotime($a['created_at']))) ?></td>
              <td>
                <form method="post" action="/admin/" style="display:flex; gap:6px;">
                  <?= csrf_field() ?>
                  <input type="hidden" name="application_id" value="<?= (int) $a['id'] ?>">
                  <select name="status" onchange="this.form.submit()">
                    <?php foreach (['new' => 'New', 'reviewing' => 'Reviewing', 'approved' => 'Approved', 'declined' => 'Declined'] as $val => $label): ?>
                      <option value="<?= $val ?>" <?= $a['status'] === $val ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                  </select>
                  <input type="hidden" name="update_status" value="1">
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$apps): ?><tr><td colspan="7" class="muted">No applications yet.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
