<?php
require_once __DIR__ . '/backend/bootstrap.php';

$pageTitle = 'Terms of Use';
$pageDescription = 'The terms governing your use of the Aloe Credit website and account.';
$canonicalPath = '/terms';
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section">
    <div class="container">
      <p class="breadcrumbs"><a href="/">Home</a> / Terms of Use</p>
      <article class="prose">
        <p class="eyebrow">Legal</p>
        <h1>Terms of Use</h1>
        <p class="muted">Last updated: <?= date('d F Y') ?></p>

        <p>These terms govern your use of the Aloe Credit website and account area. They do not constitute a loan agreement — a separate, individually signed credit agreement applies to any loan that is approved.</p>

        <h2>1. Eligibility</h2>
        <p>You must be a South African resident, at least 18 years old, and legally able to enter into a credit agreement to apply for a loan through this site.</p>

        <h2>2. Accounts</h2>
        <p>You are responsible for keeping your password confidential and for all activity under your account. Notify us immediately at <a href="mailto:hello@aloecredit.co.za">hello@aloecredit.co.za</a> if you suspect unauthorised access.</p>

        <h2>3. The loan calculator is illustrative</h2>
        <p>Figures shown by the loan repayment calculator are estimates for illustration only, based on the inputs you provide. They do not constitute an offer of credit and may differ from your actual approved rate, which depends on an affordability assessment.</p>

        <h2>4. Pre-qualification is not approval</h2>
        <p>Submitting the application form is a request for pre-qualification, not a guarantee of credit. All lending is subject to affordability assessment under the National Credit Act 34 of 2005 and may be declined.</p>

        <h2>5. Acceptable use</h2>
        <p>You agree not to misuse the site — including attempting to bypass security controls, submitting false information, or using automated tools to scrape or overload the service.</p>

        <h2>6. Intellectual property</h2>
        <p>All content, branding, and code on this site belongs to Aloe Credit (Pty) Ltd or its licensors and may not be reproduced without permission.</p>

        <h2>7. Limitation of liability</h2>
        <p>The website is provided "as is". To the extent permitted by law, Aloe Credit is not liable for indirect or consequential loss arising from use of the site, save where such liability cannot be excluded by law.</p>

        <h2>8. Governing law</h2>
        <p>These terms are governed by the laws of the Republic of South Africa.</p>

        <h2>9. Changes</h2>
        <p>We may update these terms from time to time; continued use of the site after a change constitutes acceptance of the updated terms.</p>

        <h2>10. Contact</h2>
        <p>Questions about these terms: <a href="mailto:hello@aloecredit.co.za">hello@aloecredit.co.za</a></p>
      </article>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
