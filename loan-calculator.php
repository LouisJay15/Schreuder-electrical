<?php
require_once __DIR__ . '/backend/bootstrap.php';

$pageTitle = 'Loan Repayment Calculator';
$pageDescription = 'Estimate your monthly loan repayments in seconds with the free Aloe Credit calculator. No credit check, no obligation.';
$canonicalPath = '/loan-calculator';
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://www.aloecredit.co.za/'],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Loan calculator', 'item' => 'https://www.aloecredit.co.za/loan-calculator'],
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
      <p class="breadcrumbs"><a href="/">Home</a> / Loan calculator</p>
      <p class="eyebrow">Free tool</p>
      <h1>Loan repayment calculator</h1>
      <p class="lede">Adjust the sliders to see an estimated monthly repayment. This is illustrative — your actual rate depends on an affordability assessment.</p>

      <form id="calc-form" class="grid grid-2" style="margin-top:32px; gap:32px; align-items:start;">
        <div class="card" data-reveal="left">
          <div class="field">
            <label for="calc-amount">Loan amount — <span id="calc-amount-label">R25,000</span></label>
            <input type="range" id="calc-amount" min="1000" max="250000" step="500" value="25000">
          </div>
          <div class="field">
            <label for="calc-term">Repayment term — <span id="calc-term-label">24 months</span></label>
            <input type="range" id="calc-term" min="6" max="60" step="1" value="24">
          </div>
          <div class="field">
            <label for="calc-rate">Interest rate (annual) — <span id="calc-rate-label">24.0% p.a.</span></label>
            <input type="range" id="calc-rate" min="8" max="29" step="0.5" value="24">
            <span class="hint">National Credit Act maximum for this loan type is capped at 27.5% p.a. + repo rate.</span>
          </div>
        </div>

        <div class="card" data-reveal="right" style="background:var(--ink); color:#fff; border:none;">
          <p class="eyebrow" style="color:#7cd6ac;">Estimated repayment</p>
          <h2 style="color:#fff; font-size:2.4rem; margin-bottom:4px;" id="calc-monthly">R0</h2>
          <p class="muted" style="color:#9bb3a5;">per month</p>
          <div style="display:flex; justify-content:space-between; margin-top:20px; padding-top:20px; border-top:1px solid rgba(255,255,255,.15);">
            <div><span class="muted" style="color:#9bb3a5; font-size:0.85rem;">Total repayment</span><br><strong id="calc-total" style="color:#fff;">R0</strong></div>
            <div><span class="muted" style="color:#9bb3a5; font-size:0.85rem;">Total interest</span><br><strong id="calc-interest" style="color:#fff;">R0</strong></div>
          </div>
          <a href="/apply" class="btn btn-primary btn-block" style="margin-top:24px;">Apply for this amount</a>
        </div>
      </form>

      <div style="margin-top:40px;">
        <h2 style="font-size:1.3rem;">Repayment schedule (first 12 months)</h2>
        <div class="table-wrap" style="margin-top:12px;">
          <table>
            <thead><tr><th>Month</th><th>Payment</th><th>Interest</th><th>Principal</th><th>Balance</th></tr></thead>
            <tbody id="calc-schedule-body"></tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
<script src="/assets/js/calculator.js" defer></script>
</body>
</html>
