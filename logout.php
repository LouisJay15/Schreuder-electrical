<?php
require_once __DIR__ . '/backend/bootstrap.php';
if ($user = current_user()) {
    log_event((int) $user['id'], 'logout');
}
aloe_logout();
header('Location: /');
exit;
