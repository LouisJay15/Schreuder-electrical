<?php
require_once __DIR__ . '/../backend/bootstrap.php';

$posts = [
    [
        'slug' => 'how-to-improve-your-credit-score-in-south-africa',
        'title' => 'How to Improve Your Credit Score in South Africa',
        'excerpt' => 'Practical, no-nonsense steps to raise your credit score — and why it matters the next time you apply for credit.',
        'date' => '2026-08-12',
    ],
    [
        'slug' => 'understanding-loan-interest-rates-in-south-africa',
        'title' => 'Understanding Loan Interest Rates in South Africa',
        'excerpt' => 'What the repo rate, prime rate, and NCA interest rate caps actually mean for what you repay.',
        'date' => '2026-08-05',
    ],
    [
        'slug' => '5-signs-you-should-consolidate-your-debt',
        'title' => '5 Signs You Should Consolidate Your Debt',
        'excerpt' => 'Juggling multiple accounts? Here\'s how to tell when consolidating into one loan will actually help.',
        'date' => '2026-07-22',
    ],
];

$pageTitle = 'Blog — Money Tips for South Africans';
$pageDescription = 'Practical guides on credit scores, interest rates, and borrowing responsibly, from the Aloe Credit team.';
$canonicalPath = '/blog';
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/../partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>
<main id="main">
  <section class="section">
    <div class="container">
      <p class="breadcrumbs"><a href="/">Home</a> / Blog</p>
      <p class="eyebrow">Blog</p>
      <h1>Money guidance, without the jargon.</h1>
      <p class="lede">Straight answers on credit, borrowing, and budgeting for South African readers.</p>

      <div class="grid grid-3" style="margin-top:32px;">
        <?php foreach ($posts as $p): ?>
          <a href="/blog/<?= e($p['slug']) ?>" class="card" style="text-decoration:none; color:inherit;" data-reveal>
            <p class="post-meta"><?= e(date('d M Y', strtotime($p['date']))) ?></p>
            <h3><?= e($p['title']) ?></h3>
            <p class="muted"><?= e($p['excerpt']) ?></p>
            <span style="color:var(--accent-dark); font-weight:600; font-size:0.9rem;">Read more →</span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
