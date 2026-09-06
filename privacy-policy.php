<?php
require_once __DIR__ . '/backend/bootstrap.php';

$pageTitle = 'Privacy Policy';
$pageDescription = 'How Aloe Credit collects, uses, and protects your personal information, in line with POPIA.';
$canonicalPath = '/privacy-policy';
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section">
    <div class="container">
      <p class="breadcrumbs"><a href="/">Home</a> / Privacy Policy</p>
      <article class="prose">
        <p class="eyebrow">Legal</p>
        <h1>Privacy Policy</h1>
        <p class="muted">Last updated: <?= date('d F Y') ?></p>

        <p>Aloe Credit (Pty) Ltd ("Aloe Credit", "we", "us") is committed to protecting your personal information in accordance with the Protection of Personal Information Act 4 of 2013 (POPIA). This policy explains what we collect, why, and the rights you have over it.</p>

        <h2>1. Information we collect</h2>
        <ul>
          <li>Contact details: name, email address, phone number.</li>
          <li>Financial information you provide during an application: employment status, approximate income, requested loan amount and term.</li>
          <li>Account information: a securely hashed password, and login activity used for security monitoring.</li>
          <li>Technical information: IP address and browser details, used for fraud prevention and rate limiting.</li>
        </ul>
        <p>We do not collect your ID number, banking details, or full KYC documentation through the public website. Those are only ever collected later, through a secure, authenticated process, once a formal application proceeds.</p>

        <h2>2. Why we process your information</h2>
        <ul>
          <li>To assess loan pre-qualification and affordability, as required under the National Credit Act.</li>
          <li>To create and secure your account, including two-step email verification.</li>
          <li>To respond to enquiries submitted through our contact form.</li>
          <li>To meet legal and regulatory obligations, including those owed to the National Credit Regulator.</li>
          <li>To detect and prevent fraud, abuse, and unauthorised access.</li>
        </ul>

        <h2>3. Legal basis</h2>
        <p>We process your information on the basis of your consent (given when you submit a form), the necessity of processing to assess a credit application you have requested, and our legal obligations as a registered credit provider.</p>

        <h2>4. How we protect your information</h2>
        <ul>
          <li>Passwords are hashed and never stored or transmitted in plain text.</li>
          <li>All traffic is encrypted in transit (HTTPS).</li>
          <li>Login sessions use secure, HttpOnly cookies — never local storage — and every sign-in requires a one-time email code.</li>
          <li>Access to administrative systems is restricted to authorised staff and independently verified on every request.</li>
          <li>Repeated failed login, verification, or password-reset attempts are automatically rate-limited.</li>
        </ul>

        <h2>5. Sharing your information</h2>
        <p>We do not sell your personal information. We may share it with credit bureaux (where required for a formal credit check), service providers who help us operate the platform (e.g. email delivery), and regulators or law enforcement where legally required.</p>

        <h2>6. Your rights under POPIA</h2>
        <p>You may request access to the personal information we hold about you, ask us to correct or delete it, object to certain processing, or withdraw consent (where processing relies on consent). To exercise these rights, contact our Information Officer below.</p>

        <h2>7. Data retention</h2>
        <p>We retain application and account information for as long as your account is active, and thereafter for the period required by the National Credit Act and other applicable law, after which it is securely deleted.</p>

        <h2>8. Cookies</h2>
        <p>We use a single essential session cookie to keep you logged in securely. We do not use third-party advertising or tracking cookies.</p>

        <h2>9. Contact our Information Officer</h2>
        <p>Email: <a href="mailto:privacy@aloecredit.co.za">privacy@aloecredit.co.za</a><br>
        Postal: Aloe Credit (Pty) Ltd, Cape Town, South Africa<br>
        <em>(Replace with your registered Information Officer's details before going live — POPIA requires this role to be registered with the Information Regulator.)</em></p>

        <h2>10. Changes to this policy</h2>
        <p>We may update this policy from time to time. Material changes will be reflected on this page with an updated date.</p>
      </article>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
