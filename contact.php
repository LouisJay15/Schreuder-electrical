<?php
require_once __DIR__ . '/backend/bootstrap.php';

$errors = [];
$success = false;
$values = ['name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();
    $pdo = aloe_db();

    if (!rate_limit_allow($pdo, 'contact', aloe_client_ip(), ...rate_limit_policy('contact'))) {
        rate_limit_reject();
    }

    if (($_POST['website'] ?? '') !== '') {
        $success = true;
    } else {
        $values['name'] = clean_str($_POST['name'] ?? '', 150);
        $values['email'] = strtolower(clean_str($_POST['email'] ?? '', 190));
        $values['phone'] = clean_str($_POST['phone'] ?? '', 30);
        $values['subject'] = clean_str($_POST['subject'] ?? '', 150);
        $values['message'] = clean_str($_POST['message'] ?? '', 2000);

        if ($values['name'] === '') {
            $errors['name'] = 'Enter your name.';
        }
        if (!valid_email($values['email'])) {
            $errors['email'] = 'Enter a valid email address.';
        }
        if (mb_strlen($values['message']) < 10) {
            $errors['message'] = 'Tell us a little more (at least 10 characters).';
        }

        if (!$errors) {
            $stmt = $pdo->prepare(
                'INSERT INTO contact_messages (name, email, phone, subject, message, ip, created_at)
                 VALUES (:name, :email, :phone, :subject, :message, :ip, NOW())'
            );
            $stmt->execute([
                'name' => $values['name'], 'email' => $values['email'], 'phone' => $values['phone'] ?: null,
                'subject' => $values['subject'] ?: null, 'message' => $values['message'], 'ip' => aloe_client_ip(),
            ]);
            $success = true;
        }
    }
}

$pageTitle = 'Contact us';
$pageDescription = 'Get in touch with Aloe Credit — questions about loans, applications, or your account.';
$canonicalPath = '/contact';
$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'ContactPage',
    'name' => 'Contact Aloe Credit',
];
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section">
    <div class="container grid grid-2" style="align-items:start; gap:56px;">
      <div>
        <p class="eyebrow">Contact</p>
        <h1>We're here to help.</h1>
        <p class="lede">Questions about an application, your account, or how our loans work — reach out and a real person will get back to you within one business day.</p>

        <div class="grid" style="gap:16px; margin-top:32px;">
          <div class="card">
            <h3>Email</h3>
            <p class="muted" style="margin:0;"><a href="mailto:hello@aloecredit.co.za">hello@aloecredit.co.za</a></p>
          </div>
          <div class="card">
            <h3>Phone</h3>
            <p class="muted" style="margin:0;">0800 ALOE CR (0800 256 327) · Mon–Fri, 8am–5pm SAST</p>
          </div>
          <div class="card">
            <h3>Registered office</h3>
            <p class="muted" style="margin:0;">Aloe Credit (Pty) Ltd, Cape Town, South Africa</p>
          </div>
        </div>
      </div>

      <div class="form-card">
        <?php if ($success): ?>
          <div class="alert alert-success">Thanks — your message is on its way. We'll reply within one business day.</div>
        <?php else: ?>
          <?php if ($errors): ?><div class="alert alert-error">Please fix the highlighted fields below.</div><?php endif; ?>
          <form method="post" action="/contact" novalidate>
            <?= csrf_field() ?>
            <input type="text" name="website" value="" autocomplete="off" tabindex="-1" class="visually-hidden" aria-hidden="true">
            <div class="field">
              <label for="name">Name</label>
              <input type="text" id="name" name="name" value="<?= e($values['name']) ?>" class="<?= isset($errors['name']) ? 'has-error' : '' ?>" required>
              <?php if (isset($errors['name'])): ?><span class="error"><?= e($errors['name']) ?></span><?php endif; ?>
            </div>
            <div class="grid grid-2" style="gap:16px;">
              <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= e($values['email']) ?>" class="<?= isset($errors['email']) ? 'has-error' : '' ?>" required>
                <?php if (isset($errors['email'])): ?><span class="error"><?= e($errors['email']) ?></span><?php endif; ?>
              </div>
              <div class="field">
                <label for="phone">Phone <span class="hint">(optional)</span></label>
                <input type="tel" id="phone" name="phone" value="<?= e($values['phone']) ?>">
              </div>
            </div>
            <div class="field">
              <label for="subject">Subject</label>
              <input type="text" id="subject" name="subject" value="<?= e($values['subject']) ?>">
            </div>
            <div class="field">
              <label for="message">Message</label>
              <textarea id="message" name="message" rows="5" class="<?= isset($errors['message']) ? 'has-error' : '' ?>" required><?= e($values['message']) ?></textarea>
              <?php if (isset($errors['message'])): ?><span class="error"><?= e($errors['message']) ?></span><?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Send message</button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
