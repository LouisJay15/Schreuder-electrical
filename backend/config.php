<?php
declare(strict_types=1);

require_once __DIR__ . '/env.php';

return [
    'app' => [
        'env'  => env('APP_ENV', 'production'),
        'url'  => rtrim(env('APP_URL', 'https://www.aloecredit.co.za'), '/'),
        'name' => env('APP_NAME', 'Aloe Credit'),
    ],
    'db' => [
        'host' => env('DB_HOST', 'localhost'),
        'name' => env('DB_NAME', ''),
        'user' => env('DB_USER', ''),
        'pass' => env('DB_PASS', ''),
    ],
    'mail' => [
        'from'      => env('MAIL_FROM', 'no-reply@aloecredit.co.za'),
        'from_name' => env('MAIL_FROM_NAME', 'Aloe Credit'),
        'smtp_host' => env('SMTP_HOST', ''),
        'smtp_port' => (int) env('SMTP_PORT', '587'),
        'smtp_user' => env('SMTP_USER', ''),
        'smtp_pass' => env('SMTP_PASS', ''),
        'smtp_secure' => env('SMTP_SECURE', 'tls'),
    ],
    'session_name'   => env('SESSION_NAME', 'aloe_session'),
    'hibp_leak_check' => env('HIBP_LEAK_CHECK', '1') === '1',
];
