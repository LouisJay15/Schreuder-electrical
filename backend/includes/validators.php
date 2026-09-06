<?php
declare(strict_types=1);

function valid_email(string $email): bool
{
    return (bool) filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email) <= 190;
}

/** A short, deliberately small blocklist of the most-guessed passwords; HIBP (below) catches the rest. */
const COMMON_PASSWORDS = [
    'password', 'password1', '12345678', '123456789', 'qwerty123', 'letmein123',
    'welcome123', 'iloveyou1', 'admin1234', 'aloecredit', 'creditcard', 'football1',
];

/**
 * FinTech-grade password policy: length + character-class variety + not a
 * known-common password + not trivially derived from the account's own name/email.
 * Returns an array of human-readable violations (empty = passes).
 */
function password_policy_errors(string $password, string $email = '', string $fullName = ''): array
{
    $errors = [];
    if (strlen($password) < 12) {
        $errors[] = 'Use at least 12 characters.';
    }
    if (strlen($password) > 128) {
        $errors[] = 'Password is too long.';
    }
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Include at least one lowercase letter.';
    }
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Include at least one uppercase letter.';
    }
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Include at least one number.';
    }
    if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
        $errors[] = 'Include at least one symbol.';
    }
    if (in_array(strtolower($password), COMMON_PASSWORDS, true)) {
        $errors[] = 'That password is far too common — choose something harder to guess.';
    }
    $localPart = strtolower(explode('@', $email)[0] ?? '');
    if ($localPart !== '' && str_contains(strtolower($password), $localPart)) {
        $errors[] = 'Password must not contain your email address.';
    }
    foreach (preg_split('/\s+/', trim($fullName)) as $namePart) {
        if (strlen($namePart) >= 3 && stripos($password, $namePart) !== false) {
            $errors[] = 'Password must not contain your name.';
            break;
        }
    }
    return array_values(array_unique($errors));
}

/**
 * Checks the password against the Have I Been Pwned breach corpus using
 * k-anonymity: only the first 5 chars of the SHA-1 hash ever leave the server,
 * so the real password (and its full hash) is never transmitted.
 * Fails OPEN (treats as "not found") on network/API errors so an outage never
 * blocks legitimate signups/resets — this is logged for visibility.
 */
function is_password_pwned(string $password): bool
{
    $config = require __DIR__ . '/../config.php';
    if (!$config['hibp_leak_check']) {
        return false;
    }
    $sha1 = strtoupper(sha1($password));
    $prefix = substr($sha1, 0, 5);
    $suffix = substr($sha1, 5);

    $context = stream_context_create([
        'http' => [
            'method'  => 'GET',
            'header'  => "User-Agent: AloeCredit-PasswordCheck\r\n",
            'timeout' => 3,
        ],
    ]);

    $response = @file_get_contents("https://api.pwnedpasswords.com/range/{$prefix}", false, $context);
    if ($response === false) {
        error_log('HIBP lookup failed; skipping leak check for this request.');
        return false;
    }

    foreach (explode("\r\n", trim($response)) as $line) {
        [$candidateSuffix] = explode(':', $line);
        if (hash_equals($candidateSuffix, $suffix)) {
            return true;
        }
    }
    return false;
}

function clean_str(string $value, int $maxLen = 255): string
{
    $value = trim(preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $value) ?? '');
    return mb_substr($value, 0, $maxLen);
}

function valid_phone(string $phone): bool
{
    return (bool) preg_match('/^[0-9+()\s-]{7,20}$/', $phone);
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
