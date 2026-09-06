<?php
require_once __DIR__ . '/backend/bootstrap.php';
$user = current_user();

$errors = [];
$success = false;
$values = ['full_name' => $user['full_name'] ?? '', 'email' => $user['email'] ?? '', 'phone' => '', 'employment_status' => '', 'monthly_income' => '', 'loan_amount' => '5000', 'loan_term_months' => '12', 'purpose' => ''];

$employmentOptions = ['Employed (full-time)', 'Employed (part-time)', 'Self-employed', 'Contract / freelance', 'Retired', 'Other'];
$termOptions = [6, 12, 18, 24, 36, 48, 60];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require();
    $pdo = aloe_db();

    if (!rate_limit_allow($pdo, 'apply', aloe_client_ip(), ...rate_limit_policy('apply'))) {
        rate_limit_reject();
    }

    // Honeypot: real users never fill this hidden field in.
    if (($_POST['website'] ?? '') !== '') {
        $success = true; // pretend success, drop silently
    } else {
        $values['full_name'] = clean_str($_POST['full_name'] ?? '', 150);
        $values['email'] = strtolower(clean_str($_POST['email'] ?? '', 190));
        $values['phone'] = clean_str($_POST['phone'] ?? '', 30);
        $values['employment_status'] = clean_str($_POST['employment_status'] ?? '', 60);
        $values['monthly_income'] = clean_str($_POST['monthly_income'] ?? '', 40);
        $values['loan_amount'] = clean_str($_POST['loan_amount'] ?? '', 20);
        $values['loan_term_months'] = clean_str($_POST['loan_term_months'] ?? '', 10);
        $values['purpose'] = clean_str($_POST['purpose'] ?? '', 120);
        $consent = isset($_POST['consent']);

        if ($values['full_name'] === '' || mb_strlen($values['full_name']) < 2) {
            $errors['full_name'] = 'Enter your full name.';
        }
        if (!valid_email($values['email'])) {
            $errors['email'] = 'Enter a valid email address.';
        }
        if (!valid_phone($values['phone'])) {
            $errors['phone'] = 'Enter a valid phone number.';
        }
        if (!in_array($values['employment_status'], $employmentOptions, true)) {
            $errors['employment_status'] = 'Select your employment status.';
        }
        if ($values['monthly_income'] === '' || !is_numeric($values['monthly_income']) || (float) $values['monthly_income'] <= 0) {
            $errors['monthly_income'] = 'Enter your approximate monthly income.';
        }
        $amount = is_numeric($values['loan_amount']) ? (float) $values['loan_amount'] : 0;
        if ($amount < 1000 || $amount > 250000) {
            $errors['loan_amount'] = 'Choose an amount between R1,000 and R250,000.';
        }
        if (!in_array((int) $values['loan_term_months'], $termOptions, true)) {
            $errors['loan_term_months'] = 'Select a repayment term.';
        }
        if (!$consent) {
            $errors['consent'] = 'We need your consent to process this application under POPIA.';
        }

        if (!$errors) {
            $stmt = $pdo->prepare(
                'INSERT INTO applications (user_id, full_name, email, phone, employment_status, monthly_income, loan_amount, loan_term_months, purpose, ip, created_at)
                 VALUES (:uid, :name, :email, :phone, :emp, :income, :amount, :term, :purpose, :ip, NOW())'
            );
            $stmt->execute([
                'uid'     => $user['id'] ?? null,
                'name'    => $values['full_name'],
                'email'   => $values['email'],
                'phone'   => $values['phone'],
                'emp'     => $values['employment_status'],
                'income'  => $values['monthly_income'],
                'amount'  => $amount,
                'term'    => (int) $values['loan_term_months'],
                'purpose' => $values['purpose'] ?: null,
                'ip'      => aloe_client_ip(),
            ]);
            aloe_send_mail(
                $values['email'],
                $values['full_name'],
                'We received your Aloe Credit application',
                "Hi {$values['full_name']},\n\nThanks for applying for a loan of R" . number_format($amount, 0) . " over {$values['loan_term_months']} months.\n"
                . "Our team will review your pre-qualification and be in touch within 1 business day.\n\nThis is a pre-qualification only — full approval requires an affordability assessment under the National Credit Act.\n\n— Aloe Credit"
            );
            $success = true;
        }
    }
}

$pageTitle = 'Apply for a loan';
$pageDescription = 'Apply online for an Aloe Credit personal loan in minutes. No hidden fees, transparent terms, fast pre-qualification.';
$canonicalPath = '/apply';
?><!DOCTYPE html>
<html lang="en-ZA">
<head><?php require __DIR__ . '/partials/head.php'; ?></head>
<body>
<?php require __DIR__ . '/partials/header.php'; ?>
<main id="main">
  <section class="section" style="max-width:640px; margin:0 auto;">
    <div class="container">
      <p class="eyebrow">Apply</p>
      <h1>Start your application</h1>
      <p class="lede">Two minutes now, an answer within one business day. This step is a pre-qualification check — it never affects your credit score.</p>

      <div class="form-card" style="margin-top:24px;">
        <?php if ($success): ?>
          <div class="alert alert-success">Thanks — your application is in. We've emailed you a confirmation and will be in touch within 1 business day.</div>
          <a href="/" class="btn btn-secondary">Back to home</a>
        <?php else: ?>
          <?php if ($errors): ?><div class="alert alert-error">Please fix the highlighted fields below.</div><?php endif; ?>
          <form method="post" action="/apply" novalidate>
            <?= csrf_field() ?>
            <input type="text" name="website" value="" autocomplete="off" tabindex="-1" class="visually-hidden" aria-hidden="true">

            <div class="field">
              <label for="full_name">Full name</label>
              <input type="text" id="full_name" name="full_name" value="<?= e($values['full_name']) ?>" class="<?= isset($errors['full_name']) ? 'has-error' : '' ?>" required>
              <?php if (isset($errors['full_name'])): ?><span class="error"><?= e($errors['full_name']) ?></span><?php endif; ?>
            </div>
            <div class="grid grid-2" style="gap:16px;">
              <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= e($values['email']) ?>" class="<?= isset($errors['email']) ? 'has-error' : '' ?>" required>
                <?php if (isset($errors['email'])): ?><span class="error"><?= e($errors['email']) ?></span><?php endif; ?>
              </div>
              <div class="field">
                <label for="phone">Phone</label>
                <input type="tel" id="phone" name="phone" value="<?= e($values['phone']) ?>" class="<?= isset($errors['phone']) ? 'has-error' : '' ?>" required>
                <?php if (isset($errors['phone'])): ?><span class="error"><?= e($errors['phone']) ?></span><?php endif; ?>
              </div>
            </div>
            <div class="field">
              <label for="employment_status">Employment status</label>
              <select id="employment_status" name="employment_status" class="<?= isset($errors['employment_status']) ? 'has-error' : '' ?>" required>
                <option value="">Select one</option>
                <?php foreach ($employmentOptions as $opt): ?>
                  <option value="<?= e($opt) ?>" <?= $values['employment_status'] === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
                <?php endforeach; ?>
              </select>
              <?php if (isset($errors['employment_status'])): ?><span class="error"><?= e($errors['employment_status']) ?></span><?php endif; ?>
            </div>
            <div class="field">
              <label for="monthly_income">Approximate net monthly income (R)</label>
              <input type="number" id="monthly_income" name="monthly_income" min="0" step="100" value="<?= e($values['monthly_income']) ?>" class="<?= isset($errors['monthly_income']) ? 'has-error' : '' ?>" required>
              <?php if (isset($errors['monthly_income'])): ?><span class="error"><?= e($errors['monthly_income']) ?></span><?php endif; ?>
            </div>
            <div class="grid grid-2" style="gap:16px;">
              <div class="field">
                <label for="loan_amount">Loan amount (R)</label>
                <input type="number" id="loan_amount" name="loan_amount" min="1000" max="250000" step="500" value="<?= e($values['loan_amount']) ?>" class="<?= isset($errors['loan_amount']) ? 'has-error' : '' ?>" required>
                <?php if (isset($errors['loan_amount'])): ?><span class="error"><?= e($errors['loan_amount']) ?></span><?php endif; ?>
              </div>
              <div class="field">
                <label for="loan_term_months">Repayment term</label>
                <select id="loan_term_months" name="loan_term_months" class="<?= isset($errors['loan_term_months']) ? 'has-error' : '' ?>" required>
                  <?php foreach ($termOptions as $m): ?>
                    <option value="<?= $m ?>" <?= (int) $values['loan_term_months'] === $m ? 'selected' : '' ?>><?= $m ?> months</option>
                  <?php endforeach; ?>
                </select>
                <?php if (isset($errors['loan_term_months'])): ?><span class="error"><?= e($errors['loan_term_months']) ?></span><?php endif; ?>
              </div>
            </div>
            <div class="field">
              <label for="purpose">What's the loan for? <span class="hint">(optional)</span></label>
              <input type="text" id="purpose" name="purpose" value="<?= e($values['purpose']) ?>">
            </div>
            <label class="checkbox-row" style="margin-bottom:20px;">
              <input type="checkbox" name="consent" <?= isset($_POST['consent']) ? 'checked' : '' ?>>
              <span>I consent to Aloe Credit processing my information to assess this application, in line with the <a href="/privacy-policy">Privacy Policy</a> and POPIA.</span>
            </label>
            <?php if (isset($errors['consent'])): ?><p class="error" style="margin-top:-12px;"><?= e($errors['consent']) ?></p><?php endif; ?>
            <button type="submit" class="btn btn-primary btn-block">Submit application</button>
            <p class="hint" style="margin-top:12px; text-align:center;">Credit granted subject to affordability assessment under the National Credit Act.</p>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
