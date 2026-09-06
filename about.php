<?php
require_once __DIR__ . '/backend/bootstrap.php';

$pageTitle = 'About Aloe Credit';
$pageDescription = 'Aloe Credit is a South African credit provider built around one idea: lending should be clear enough to explain in a minute.';
$canonicalPath = '/about';
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'AboutPage',
    'name' => 'About Aloe Credit',
    'url' => 'https://www.aloecredit.co.za/about',
];
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section">
    <div class="container">
      <p class="breadcrumbs"><a href="/">Home</a> / About</p>
      <div class="hero-grid" style="min-height:auto; padding:0;">
        <div data-reveal="left">
          <p class="eyebrow">Our story</p>
          <h1>Lending shouldn't need a translator.</h1>
          <p class="lede">Aloe Credit was started with one frustration: loan agreements written to be technically correct and practically unreadable. We build the opposite — terms a first-time borrower can actually understand.</p>
        </div>
        <div class="hero-art hero-art-visual" data-reveal="right" style="box-shadow:none; border:1px solid var(--line);">
          <?php require __DIR__ . '/partials/aloe-visual.php'; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="section section-alt">
    <div class="container">
      <div class="section-head">
        <p class="eyebrow">Why "Aloe"</p>
        <h2>Resilient by nature.</h2>
      </div>
      <p class="lede">The aloe plant survives where little else does — storing what it needs, wasting nothing. That's the standard we hold our loans to: no bloated fees stored away in the fine print, no more borrowed than a budget can absorb.</p>
    </div>
  </section>

  <section class="section">
    <div class="container">
      <div class="section-head">
        <h2>What we stand for</h2>
      </div>
      <div class="grid grid-3">
        <div class="card" data-reveal>
          <div class="icon-tile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 3 7v6c0 5 4 9 9 9s9-4 9-9V7Z"/></svg></div>
          <h3>Responsible by default</h3>
          <p class="muted">We assess affordability on every application. If a loan isn't a good fit for your budget, we'll tell you — even if that means saying no.</p>
        </div>
        <div class="card" data-reveal>
          <div class="icon-tile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/></svg></div>
          <h3>Priced in plain sight</h3>
          <p class="muted">Interest, fees, and total repayment are shown together, before you sign — never split across pages of legal text.</p>
        </div>
        <div class="card" data-reveal>
          <div class="icon-tile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
          <h3>Security first</h3>
          <p class="muted">Your data is encrypted, your login is protected by two-step verification, and we never store more than we need.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="section section-alt">
    <div class="container" style="max-width:760px;">
      <p class="eyebrow">Leadership</p>
      <h2>Run by credit and technology specialists.</h2>
      <p class="lede">Aloe Credit is led by a small team with backgrounds in consumer credit risk, compliance, and financial software — brought together to build a lender we'd want to borrow from ourselves.</p>
    </div>
  </section>

  <section class="section section-ink" style="text-align:center;">
    <div class="container">
      <h2>Ready to see your rate?</h2>
      <div class="hero-cta" style="justify-content:center;">
        <a href="/apply" class="btn btn-primary">Apply Now</a>
        <a href="/services" class="btn btn-secondary">Explore loan options</a>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
