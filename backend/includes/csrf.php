<?php
declare(strict_types=1);

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES) . '">';
}

function csrf_verify(?string $token): bool
{
    return is_string($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrf_require(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!csrf_verify($token)) {
        http_response_code(419);
        header('Content-Type: application/json');
        echo json_encode(['ok' => false, 'error' => 'Your session expired. Please refresh and try again.']);
        exit;
    }
}
