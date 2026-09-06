<?php
require_once __DIR__ . '/backend/bootstrap.php';

$pageTitle = 'Personal Loans in South Africa — Clear Terms, Fast Decisions';
$pageDescription = 'Aloe Credit offers responsible personal loans for South Africans: transparent rates, no hidden fees, and a decision within one business day.';
$canonicalPath = '/';
$jsonLd = [
    [
        '@context' => 'https://schema.org',
        '@type' => 'FinancialService',
        'name' => 'Aloe Credit',
        'url' => 'https://www.aloecredit.co.za',
        'logo' => 'https://www.aloecredit.co.za/assets/img/logo.svg',
        'description' => $pageDescription,
        'areaServed' => 'ZA',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Cape Town',
            'addressCountry' => 'ZA',
        ],
        'telephone' => '+27-800-256-327',
    ],
    [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [[
            '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => 'https://www.aloecredit.co.za/',
        ]],
    ],
];
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">

  <section class="hero">
    <div class="container hero-grid">
      <div>
        <span class="badge" style="background:rgba(30,143,94,.18); color:#7cd6ac;">NCR-registered credit provider</span>
        <h1 style="margin-top:16px;">Borrow with clarity, not small print.</h1>
        <p class="lede">A personal loan that tells you the real rate up front, with a decision in one business day — not a week of paperwork.</p>
        <div class="hero-cta">
          <a href="/apply" class="btn btn-primary">Apply Now</a>
          <a href="/loan-calculator" class="btn btn-secondary">Calculate repayments</a>
        </div>
        <div class="hero-stats">
          <div><strong>1 business day</strong><span>Typical decision time</span></div>
          <div><strong>R1,000–R250,000</strong><span>Loan amounts</span></div>
          <div><strong>6–60 months</strong><span>Flexible terms</span></div>
        </div>
      </div>
      <div class="hero-art">
        <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=1200&h=1000&fit=crop&auto=format&q=80" alt="A small South African business owner reviewing paperwork at her shop counter" width="1200" height="1000" loading="eager">
      </div>
    </div>
  </section>

  <section class="section-tight section-alt">
    <div class="container">
      <div class="grid grid-4" style="gap:20px;">
        <div style="text-align:center;">
          <strong style="display:block; font-family:var(--font-display); color:var(--ink);">POPIA compliant</strong>
          <span class="muted" style="font-size:0.88rem;">Your data, protected</span>
        </div>
        <div style="text-align:center;">
          <strong style="display:block; font-family:var(--font-display); color:var(--ink);">NCA aligned</strong>
          <span class="muted" style="font-size:0.88rem;">Responsible lending, always</span>
        </div>
        <div style="text-align:center;">
          <strong style="display:block; font-family:var(--font-display); color:var(--ink);">No hidden fees</strong>
          <span class="muted" style="font-size:0.88rem;">The rate you see is the rate you pay</span>
        </div>
        <div style="text-align:center;">
          <strong style="display:block; font-family:var(--font-display); color:var(--ink);">Bank-grade security</strong>
          <span class="muted" style="font-size:0.88rem;">Encrypted end to end</span>
        </div>
      </div>
    </div>
  </section>

  <section class="section" id="how-it-works">
    <div class="container">
      <div class="section-head">
        <p class="eyebrow">How it works</p>
        <h2>Three steps to a decision.</h2>
      </div>
      <div class="grid grid-3">
        <div class="card" data-reveal>
          <div class="icon-tile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg></div>
          <h3>1. Tell us what you need</h3>
          <p class="muted">Answer a few questions about your income and the amount you're after. Takes about two minutes.</p>
        </div>
        <div class="card" data-reveal>
          <div class="icon-tile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
          <h3>2. Get a fast decision</h3>
          <p class="muted">We run an affordability assessment and reply within one business day — no surprises buried in fine print.</p>
        </div>
        <div class="card" data-reveal>
          <div class="icon-tile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg></div>
          <h3>3. Funds in your account</h3>
          <p class="muted">Accept your offer and the money is paid out directly to your bank account — no branch visit required.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section section-alt">
    <div class="container">
      <div class="grid grid-2" style="gap:24px; align-items:stretch;">
        <div class="card" data-reveal>
          <h2 style="font-size:1.5rem;">Every rand, explained.</h2>
          <p class="muted">Before you sign anything, you'll see the interest rate, the initiation fee, monthly service fee, and total repayment — laid out in one place, in plain language.</p>
          <a href="/loan-calculator" class="btn btn-secondary" style="margin-top:8px;">Try the calculator</a>
        </div>
        <div class="card card-flush" data-reveal>
          <img src="https://images.unsplash.com/photo-1554774853-b415df9eeb92?w=900&h=700&fit=crop&auto=format&q=80" alt="Close-up of a phone displaying a loan repayment schedule" width="900" height="700" loading="lazy" style="height:100%; object-fit:cover;">
        </div>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-head">
        <h2>Built for real South African budgets.</h2>
        <p class="lede">Whatever the loan is for, the process stays the same: clear terms and a repayment plan that fits your income.</p>
      </div>
      <div class="grid grid-3">
        <div class="card" data-reveal>
          <h3>Debt consolidation</h3>
          <p class="muted">Roll multiple accounts into one predictable monthly payment.</p>
        </div>
        <div class="card" data-reveal>
          <h3>Home improvements</h3>
          <p class="muted">Fund repairs or upgrades without draining your savings.</p>
        </div>
        <div class="card" data-reveal>
          <h3>Life's big moments</h3>
          <p class="muted">Medical costs, education, or a family event — covered without the wait.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section section-ink">
    <div class="container" style="text-align:center;">
      <h2>See what you'd repay before you apply.</h2>
      <p class="lede" style="margin:0 auto 28px; color:#c3d4cb;">No credit check, no obligation — just the numbers, up front.</p>
      <div class="hero-cta" style="justify-content:center;">
        <a href="/apply" class="btn btn-primary">Apply Now</a>
        <a href="/loan-calculator" class="btn btn-secondary">Calculate repayments</a>
      </div>
    </div>
  </section>

  <section class="section">
    <div class="container" style="max-width:760px;">
      <div class="section-head" style="max-width:none;">
        <h2>Common questions</h2>
      </div>
      <div data-accordion>
        <div class="accordion-item is-open">
          <button class="accordion-trigger" aria-expanded="true">
            How fast will I get a decision?
            <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
          </button>
          <div class="accordion-panel"><div class="accordion-panel-inner">Most applicants hear back within one business day of submitting a complete application.</div></div>
        </div>
        <div class="accordion-item">
          <button class="accordion-trigger" aria-expanded="false">
            Will applying affect my credit score?
            <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
          </button>
          <div class="accordion-panel"><div class="accordion-panel-inner">Pre-qualifying through our form does not affect your credit score. A full credit check only happens if you proceed to a formal offer.</div></div>
        </div>
        <div class="accordion-item">
          <button class="accordion-trigger" aria-expanded="false">
            Is Aloe Credit a registered credit provider?
            <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
          </button>
          <div class="accordion-panel"><div class="accordion-panel-inner">Yes — Aloe Credit is registered with the National Credit Regulator and operates under the National Credit Act 34 of 2005.</div></div>
        </div>
      </div>
      <p style="margin-top:24px;"><a href="/faq">See all frequently asked questions →</a></p>
    </div>
  </section>

</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
