<?php
declare(strict_types=1);

/** Applied on every request (front-end pages and API endpoints alike). */
function aloe_security_headers(): void
{
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), camera=(), microphone=(), payment=()');
    header("Content-Security-Policy: default-src 'self'; " .
        "img-src 'self' data:; " .
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
        "font-src 'self' https://fonts.gstatic.com; " .
        "script-src 'self'; " .
        "connect-src 'self'; " .
        "frame-ancestors 'none'; base-uri 'self'; form-action 'self'");
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        header('Strict-Transport-Security: max-age=63072000; includeSubDomains; preload');
    }
}
aloe_security_headers();
