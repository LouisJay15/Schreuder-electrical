<?php
require_once __DIR__ . '/../backend/bootstrap.php';

$pageTitle = 'Understanding Loan Interest Rates in South Africa';
$pageDescription = 'What the repo rate, prime rate, and NCA interest rate caps actually mean for what you repay on a personal loan.';
$canonicalPath = '/blog/understanding-loan-interest-rates-in-south-africa';
$publishDate = '2026-08-05';
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $pageTitle,
    'description' => $pageDescription,
    'datePublished' => $publishDate,
    'dateModified' => $publishDate,
    'author' => ['@type' => 'Organization', 'name' => 'Aloe Credit'],
    'publisher' => ['@type' => 'Organization', 'name' => 'Aloe Credit', 'logo' => ['@type' => 'ImageObject', 'url' => 'https://www.aloecredit.co.za/assets/img/logo.svg']],
    'mainEntityOfPage' => 'https://www.aloecredit.co.za' . $canonicalPath,
];
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/../partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/../partials/header.php'; ?>
<main id="main">
  <section class="section" style="max-width:720px; margin:0 auto;">
    <div class="container">
      <p class="breadcrumbs"><a href="/">Home</a> / <a href="/blog">Blog</a> / Interest rates</p>
      <p class="post-meta">Published <?= e(date('d F Y', strtotime($publishDate))) ?> · Aloe Credit Team</p>
      <h1>Understanding Loan Interest Rates in South Africa</h1>

      <article class="prose">
        <p>"What's the interest rate?" is the right first question to ask about any loan — but the answer involves a few moving parts that are worth understanding before you sign anything.</p>

        <h2>The repo rate sets the floor</h2>
        <p>The South African Reserve Bank sets the repurchase (repo) rate — the rate at which it lends to commercial banks. When the repo rate rises, borrowing generally gets more expensive across the economy; when it falls, rates tend to ease. This is the base everything else is built on.</p>

        <h2>Prime rate: repo rate plus a margin</h2>
        <p>Banks add a margin on top of the repo rate to set their prime lending rate. Personal loan rates are usually quoted relative to prime — for example, "prime plus 6%" — rather than as a flat number, because it moves with the repo rate over time.</p>

        <h2>The NCA caps how high rates can go</h2>
        <p>The National Credit Act sets maximum interest rates by credit type, recalculated against the repo rate, so lenders can't charge unlimited rates regardless of risk. For unsecured personal loans, the cap is repo rate plus 21%, expressed as a maximum annual percentage. Any registered credit provider — Aloe Credit included — must price within that ceiling.</p>

        <h2>Interest rate vs. total cost of credit</h2>
        <p>The interest rate is only part of the picture. The total cost of a loan also includes:</p>
        <ul>
          <li><strong>Initiation fee</strong> — a once-off setup cost, also capped under the NCA.</li>
          <li><strong>Monthly service fee</strong> — ongoing account administration.</li>
          <li><strong>Optional credit life insurance</strong> — settles your balance if you die or become disabled.</li>
        </ul>
        <p>Two loans with the same interest rate can have different total costs once fees are added — always compare the total repayment, not just the headline rate.</p>

        <h2>Fixed vs. variable rate</h2>
        <p>A fixed rate stays the same for the life of the loan, so your repayment never changes. A variable rate moves with prime, which can work in your favour if rates fall — or against you if they rise. Most short-to-medium term personal loans use a fixed rate specifically so your budget stays predictable.</p>

        <h2>See your actual number</h2>
        <p>Rather than estimate in your head, plug your amount and term into our <a href="/loan-calculator">loan repayment calculator</a> to see an illustrative monthly payment, total repayment, and total interest side by side. When you're ready, you can <a href="/apply">apply for pre-qualification</a> to get your real, personalised rate.</p>
      </article>

      <p style="margin-top:32px;"><a href="/blog">← Back to all articles</a></p>
    </div>
  </section>
</main>
<?php require __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
