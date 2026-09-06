<?php
require_once __DIR__ . '/backend/bootstrap.php';

$pageTitle = 'Responsible Lending & NCA Disclosures';
$pageDescription = 'How Aloe Credit assesses affordability, prevents reckless lending, and supports customers under the National Credit Act.';
$canonicalPath = '/responsible-lending';
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section">
    <div class="container">
      <p class="breadcrumbs"><a href="/">Home</a> / Responsible Lending</p>
      <article class="prose">
        <p class="eyebrow">Compliance</p>
        <h1>Responsible lending</h1>
        <p class="lede">Aloe Credit (Pty) Ltd is registered with the National Credit Regulator (NCR registration number NCRCP-XXXXXX) and operates under the National Credit Act 34 of 2005 ("the NCA").</p>

        <h2>How we assess affordability</h2>
        <p>Before any loan is approved, we review your income, existing debt obligations, and living expenses to confirm the repayment fits comfortably within your budget. If it doesn't, we will decline the application or offer a smaller amount — this is a legal requirement, not a formality.</p>

        <h2>Never borrow more than you can afford</h2>
        <p>Warning signs of over-indebtedness include: using credit to pay off other credit, only ever making minimum payments, or regularly running out of money before your next payday. If any of this sounds familiar, speak to a registered debt counsellor before taking on new credit.</p>

        <h2>Your right to debt counselling</h2>
        <p>If you are over-indebted, you have the right under the NCA to apply for debt review with a registered debt counsellor, who can help restructure your repayments across all your credit agreements. Find a registered debt counsellor via the National Credit Regulator.</p>

        <h2>Cooling-off and early settlement</h2>
        <p>You may settle your Aloe Credit loan early at any time, without penalty, and pay only the interest accrued to that date.</p>

        <h2>How to lodge a complaint</h2>
        <p>If you're unhappy with any part of your experience, contact us first at <a href="mailto:hello@aloecredit.co.za">hello@aloecredit.co.za</a>. If we can't resolve it, you may escalate to:</p>
        <ul>
          <li><strong>National Credit Regulator (NCR)</strong> — for complaints about credit providers.</li>
          <li><strong>National Consumer Tribunal</strong> — for formal disputes under the NCA.</li>
        </ul>
        <p><em>(Insert your NCR registration number and the NCR's current contact details before this page goes live.)</em></p>
      </article>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
