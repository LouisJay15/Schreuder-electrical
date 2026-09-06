<?php
declare(strict_types=1);

require_once __DIR__ . '/../db.php';

function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }
    static $user = null;
    if ($user !== null) {
        return $user;
    }
    $stmt = aloe_db()->prepare('SELECT id, full_name, email, role, status FROM users WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $_SESSION['user_id']]);
    $row = $stmt->fetch();
    if (!$row || $row['status'] !== 'active') {
        aloe_logout();
        return null;
    }
    $user = $row;
    return $user;
}

function require_login(): array
{
    $user = current_user();
    if (!$user) {
        header('Location: /login.php?next=' . urlencode($_SERVER['REQUEST_URI'] ?? '/'));
        exit;
    }
    return $user;
}

/**
 * The admin gate. Re-reads the role from the database on every call instead
 * of trusting a cached session value — a client can never elevate itself by
 * tampering with anything on their end, because nothing client-side is
 * consulted at all. A demoted admin loses access on their very next request.
 */
function require_admin(): array
{
    $user = require_login();
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        require __DIR__ . '/../../403.php';
        exit;
    }
    return $user;
}

function log_event(?int $userId, string $event, string $detail = ''): void
{
    $stmt = aloe_db()->prepare(
        'INSERT INTO audit_log (user_id, event, detail, ip, user_agent, created_at)
         VALUES (:uid, :event, :detail, :ip, :ua, NOW())'
    );
    $stmt->execute([
        'uid'    => $userId,
        'event'  => $event,
        'detail' => mb_substr($detail, 0, 255),
        'ip'     => aloe_client_ip(),
        'ua'     => mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 255),
    ]);
}

function generate_otp(): string
{
    return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

function issue_login_otp(int $userId, string $purpose = 'login_2fa'): string
{
    $pdo = aloe_db();
    // Invalidate any earlier unconsumed codes for this purpose first.
    $pdo->prepare('UPDATE otp_codes SET consumed_at = NOW() WHERE user_id = :uid AND purpose = :p AND consumed_at IS NULL')
        ->execute(['uid' => $userId, 'p' => $purpose]);

    $code = generate_otp();
    $stmt = $pdo->prepare(
        'INSERT INTO otp_codes (user_id, code_hash, purpose, expires_at, created_at)
         VALUES (:uid, :hash, :purpose, DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW())'
    );
    $stmt->execute(['uid' => $userId, 'hash' => hash('sha256', $code), 'purpose' => $purpose]);
    return $code;
}

/** @return string 'ok'|'invalid'|'expired'|'too_many_attempts' */
function verify_login_otp(int $userId, string $code, string $purpose = 'login_2fa'): string
{
    $pdo = aloe_db();
    $stmt = $pdo->prepare(
        'SELECT id, code_hash, expires_at, attempts FROM otp_codes
         WHERE user_id = :uid AND purpose = :p AND consumed_at IS NULL
         ORDER BY id DESC LIMIT 1'
    );
    $stmt->execute(['uid' => $userId, 'p' => $purpose]);
    $row = $stmt->fetch();
    if (!$row) {
        return 'invalid';
    }
    if ((int) $row['attempts'] >= 5) {
        return 'too_many_attempts';
    }
    if (strtotime($row['expires_at']) < time()) {
        return 'expired';
    }

    $pdo->prepare('UPDATE otp_codes SET attempts = attempts + 1 WHERE id = :id')->execute(['id' => $row['id']]);

    if (!hash_equals($row['code_hash'], hash('sha256', $code))) {
        return 'invalid';
    }
    $pdo->prepare('UPDATE otp_codes SET consumed_at = NOW() WHERE id = :id')->execute(['id' => $row['id']]);
    return 'ok';
}

function login_user(array $user): void
{
    aloe_regenerate_session();
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['role'] = $user['role'];
    unset($_SESSION['pending_2fa_user_id'], $_SESSION['pending_2fa_expires']);
}
