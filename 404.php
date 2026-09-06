<?php
require_once __DIR__ . '/backend/bootstrap.php';
http_response_code(404);
$pageTitle = 'Page not found';
$pageDescription = 'The page you were looking for could not be found.';
$canonicalPath = '/404';
$noindex = true;
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section" style="text-align:center;">
    <div class="container">
      <p class="eyebrow">404</p>
      <h1>We couldn't find that page.</h1>
      <p class="lede" style="margin:0 auto 28px;">The link may be broken, or the page may have moved. Try the calculator, or head back home.</p>
      <div class="hero-cta" style="justify-content:center;">
        <a href="/" class="btn btn-primary">Back to home</a>
        <a href="/loan-calculator" class="btn btn-secondary">Try the loan calculator</a>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
