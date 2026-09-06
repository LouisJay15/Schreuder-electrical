<?php
declare(strict_types=1);

/**
 * Hardened, cookie-based PHP session. The session ID is the ONLY thing that
 * ever touches the client — as an HttpOnly, Secure, SameSite=Strict cookie.
 * No auth token, role, or user id is ever written to localStorage/sessionStorage;
 * front-end JS has no access to session state at all, which is what stops a
 * successful XSS from being able to steal or forge a login.
 */

if (session_status() === PHP_SESSION_NONE) {
    $aloeConfig = require __DIR__ . '/../config.php';

    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

    session_name($aloeConfig['session_name']);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_start();

    // Idle timeout (30 min) and absolute timeout (8 h), enforced server-side.
    $now = time();
    if (isset($_SESSION['last_active']) && ($now - $_SESSION['last_active']) > 1800) {
        aloe_logout();
    } elseif (isset($_SESSION['started_at']) && ($now - $_SESSION['started_at']) > 28800) {
        aloe_logout();
    }
    $_SESSION['last_active'] = $now;
}

function aloe_regenerate_session(): void
{
    session_regenerate_id(true);
    $_SESSION['started_at'] = time();
    $_SESSION['last_active'] = time();
}

function aloe_logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}
