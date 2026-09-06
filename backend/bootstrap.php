<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/security_headers.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/csrf.php';
require_once __DIR__ . '/includes/rate_limit.php';
require_once __DIR__ . '/includes/validators.php';
require_once __DIR__ . '/includes/mailer.php';
require_once __DIR__ . '/includes/auth.php';

if (!defined('ALOE_BOOTSTRAPPED')) {
    define('ALOE_BOOTSTRAPPED', true);
}
