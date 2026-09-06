<?php
if (!defined('ALOE_BOOTSTRAPPED')) {
    require_once __DIR__ . '/backend/bootstrap.php';
}
http_response_code(403);
$pageTitle = 'Access denied';
$canonicalPath = '/403';
$noindex = true;
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section" style="text-align:center;">
    <div class="container">
      <p class="eyebrow">403</p>
      <h1>You don't have access to this page.</h1>
      <p class="lede" style="margin:0 auto 28px;">If you think this is a mistake, contact us.</p>
      <a href="/" class="btn btn-primary">Back to home</a>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
