<?php
declare(strict_types=1);

/**
 * Sliding-window rate limiter backed by rate_limit_events.
 * Applied to every auth-adjacent endpoint per the FinTech-standard pattern of
 * layering a tight per-account/IP limit with a looser per-IP limit, so a
 * single stolen identifier can't be brute-forced and one IP can't spray many
 * accounts either.
 */

function aloe_client_ip(): string
{
    $forwarded = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
    if ($forwarded !== '') {
        $parts = explode(',', $forwarded);
        return trim($parts[0]);
    }
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function rate_limit_count(PDO $pdo, string $bucket, string $identifier, int $windowSeconds): int
{
    $stmt = $pdo->prepare(
        'SELECT COUNT(*) FROM rate_limit_events
         WHERE bucket = :bucket AND identifier = :identifier
           AND created_at >= (NOW() - INTERVAL :window SECOND)'
    );
    $stmt->execute(['bucket' => $bucket, 'identifier' => $identifier, 'window' => $windowSeconds]);
    return (int) $stmt->fetchColumn();
}

function rate_limit_record(PDO $pdo, string $bucket, string $identifier): void
{
    $stmt = $pdo->prepare('INSERT INTO rate_limit_events (bucket, identifier, created_at) VALUES (:b, :i, NOW())');
    $stmt->execute(['b' => $bucket, 'i' => $identifier]);
}

/** Returns true when the action is still allowed under the limit; records the attempt either way. */
function rate_limit_allow(PDO $pdo, string $bucket, string $identifier, int $max, int $windowSeconds): bool
{
    $count = rate_limit_count($pdo, $bucket, $identifier, $windowSeconds);
    rate_limit_record($pdo, $bucket, $identifier);
    return $count < $max;
}

/** Per-endpoint limit presets, expressed as [max attempts, window seconds]. */
function rate_limit_policy(string $bucket): array
{
    return match ($bucket) {
        'login_account'      => [5, 900],    // 5 / 15 min per ip|email
        'login_ip'           => [20, 900],   // 20 / 15 min per ip (credential-stuffing guard)
        'otp_verify'         => [5, 600],    // 5 / 10 min per user
        'otp_resend'         => [3, 600],    // 3 / 10 min per user
        'password_reset_account' => [3, 3600], // 3 / hour per ip|email
        'password_reset_ip'  => [8, 3600],   // 8 / hour per ip
        'password_reset_confirm' => [10, 3600], // 10 / hour per ip (token itself is the real gate)
        'register'           => [5, 3600],   // 5 / hour per ip
        'contact'             => [5, 3600],  // 5 / hour per ip
        'apply'                => [5, 3600], // 5 / hour per ip
        default              => [10, 600],
    };
}

/** JSON 429 helper for API endpoints. */
function rate_limit_reject(): void
{
    http_response_code(429);
    header('Content-Type: application/json');
    header('Retry-After: 900');
    echo json_encode(['ok' => false, 'error' => 'Too many attempts. Please wait a while and try again.']);
    exit;
}
