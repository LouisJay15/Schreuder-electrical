<?php
require_once __DIR__ . '/backend/bootstrap.php';

$faqs = [
    ['q' => 'How much can I borrow?', 'a' => 'Aloe Credit offers personal loans from R1,000 to R250,000, depending on your affordability assessment.'],
    ['q' => 'What documents do I need to apply?', 'a' => 'Start with just your name, contact details, employment status, and income. If you\'re pre-qualified, we\'ll ask for a payslip and bank statements to confirm affordability.'],
    ['q' => 'Will applying affect my credit score?', 'a' => 'No. Pre-qualifying through our online form is a soft check that does not affect your credit score. A full credit check only happens once you proceed to a formal offer.'],
    ['q' => 'How long does approval take?', 'a' => 'Most applicants receive a decision within one business day of submitting a complete application.'],
    ['q' => 'What interest rate will I pay?', 'a' => 'Your rate depends on your credit profile and affordability. All rates are capped in line with the National Credit Act, and shown in full before you accept any offer.'],
    ['q' => 'Can I settle my loan early?', 'a' => 'Yes — there is no penalty for early settlement. Paying off your loan sooner reduces the total interest you pay.'],
    ['q' => 'What happens if I miss a payment?', 'a' => 'Contact us as soon as you know you\'ll miss a payment. We\'d rather help you restructure your repayment than let it default — reach out via the contact page.'],
    ['q' => 'Is my information secure?', 'a' => 'Yes. All data is encrypted in transit, passwords are never stored in plain text, and every login is protected by two-step email verification.'],
    ['q' => 'Is Aloe Credit a registered credit provider?', 'a' => 'Yes — Aloe Credit (Pty) Ltd is registered with the National Credit Regulator (NCR) and operates under the National Credit Act 34 of 2005.'],
];

$pageTitle = 'Frequently Asked Questions';
$pageDescription = 'Answers to common questions about Aloe Credit personal loans: eligibility, rates, approval times, and security.';
$canonicalPath = '/faq';
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => array_map(fn($f) => [
        '@type' => 'Question',
        'name' => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ], $faqs),
];
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section" style="max-width:780px; margin:0 auto;">
    <div class="container">
      <p class="breadcrumbs"><a href="/">Home</a> / FAQ</p>
      <p class="eyebrow">FAQ</p>
      <h1>Frequently asked questions</h1>
      <div data-accordion style="margin-top:24px;">
        <?php foreach ($faqs as $i => $f): ?>
          <div class="accordion-item<?= $i === 0 ? ' is-open' : '' ?>">
            <button class="accordion-trigger" aria-expanded="<?= $i === 0 ? 'true' : 'false' ?>">
              <?= e($f['q']) ?>
              <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
            </button>
            <div class="accordion-panel"><div class="accordion-panel-inner"><?= e($f['a']) ?></div></div>
          </div>
        <?php endforeach; ?>
      </div>
      <p style="margin-top:32px;">Still have a question? <a href="/contact">Get in touch</a>.</p>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
