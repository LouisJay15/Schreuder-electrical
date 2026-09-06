<?php
require_once __DIR__ . '/backend/bootstrap.php';

$pageTitle = 'Personal Loan Products';
$pageDescription = 'Explore Aloe Credit loan options: personal loans, debt consolidation, and short-term credit — with transparent rates and flexible terms.';
$canonicalPath = '/services';
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://www.aloecredit.co.za/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Loan products', 'item' => 'https://www.aloecredit.co.za/services'],
    ],
];
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section">
    <div class="container">
      <p class="breadcrumbs"><a href="/">Home</a> / Loan products</p>
      <p class="eyebrow">Loan products</p>
      <h1>One application, three ways to use it.</h1>
      <p class="lede">Every product shares the same transparent pricing model — only the term and purpose change.</p>
    </div>
  </section>

  <section class="section-tight">
    <div class="container grid grid-3">
      <div class="card" data-reveal>
        <div class="icon-tile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg></div>
        <h3>Personal loans</h3>
        <p class="muted">R1,000 – R250,000 for whatever life needs covering — repaid over 6 to 60 months.</p>
        <ul style="padding-left:18px; color:var(--text-muted); font-size:0.92rem;">
          <li>Fixed monthly repayments</li>
          <li>No early-settlement penalty</li>
          <li>Funds paid within 24 hours of acceptance</li>
        </ul>
      </div>
      <div class="card" data-reveal>
        <div class="icon-tile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.85 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg></div>
        <h3>Debt consolidation</h3>
        <p class="muted">Combine store cards, credit cards, and other loans into a single, lower monthly payment.</p>
        <ul style="padding-left:18px; color:var(--text-muted); font-size:0.92rem;">
          <li>One due date instead of several</li>
          <li>Often a lower blended interest rate</li>
          <li>We help you compare before you switch</li>
        </ul>
      </div>
      <div class="card" data-reveal>
        <div class="icon-tile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
        <h3>Short-term credit</h3>
        <p class="muted">Smaller amounts over 6–12 months for a quicker gap to bridge — same transparent terms, shorter commitment.</p>
        <ul style="padding-left:18px; color:var(--text-muted); font-size:0.92rem;">
          <li>Faster affordability review</li>
          <li>Lower total interest for shorter terms</li>
          <li>Ideal for once-off expenses</li>
        </ul>
      </div>
    </div>
  </section>

  <section class="section section-alt">
    <div class="container">
      <div class="section-head">
        <p class="eyebrow">Pricing</p>
        <h2>What's actually included</h2>
      </div>
      <div class="table-wrap">
        <table>
          <thead><tr><th>Cost</th><th>What it covers</th><th>When it's shown</th></tr></thead>
          <tbody>
            <tr><td>Interest rate</td><td>The cost of borrowing, as an annual percentage</td><td>Before you apply, and in your offer</td></tr>
            <tr><td>Initiation fee</td><td>Once-off fee for setting up the loan, capped under the NCA</td><td>In your offer, itemised</td></tr>
            <tr><td>Monthly service fee</td><td>Ongoing account administration</td><td>In your offer, itemised</td></tr>
            <tr><td>Credit life insurance</td><td>Optional cover that settles your balance in case of death or disability</td><td>Optional — you choose at offer stage</td></tr>
          </tbody>
        </table>
      </div>
      <p class="muted" style="margin-top:16px;">All fees are capped in line with the National Credit Act. Nothing is added after you've signed.</p>
    </div>
  </section>

  <section class="section section-ink" style="text-align:center;">
    <div class="container">
      <h2>See your numbers before you decide.</h2>
      <div class="hero-cta" style="justify-content:center;">
        <a href="/apply" class="btn btn-primary">Apply Now</a>
        <a href="/loan-calculator" class="btn btn-secondary">Calculate repayments</a>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
