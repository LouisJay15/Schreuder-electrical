<?php
require_once __DIR__ . '/backend/bootstrap.php';
require_once __DIR__ . '/backend/includes/guide_knowledge.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$body = json_decode($raw ?: '', true);
$csrfToken = is_array($body) ? ($body['csrf_token'] ?? '') : ($_POST['csrf_token'] ?? '');
if (!csrf_verify($csrfToken)) {
    http_response_code(419);
    echo json_encode(['ok' => false, 'error' => 'Your session expired. Please refresh and try again.']);
    exit;
}

if (!rate_limit_allow(aloe_db(), 'chat', aloe_client_ip(), ...rate_limit_policy('chat'))) {
    rate_limit_reject();
}

$message = is_array($body) ? ($body['message'] ?? '') : ($_POST['message'] ?? '');
$message = clean_str((string) $message, 300);

if ($message === '') {
    echo json_encode(['ok' => true, 'reply' => 'Ask me something about Aloe Credit\'s loans — amounts, rates, eligibility, or how to apply.', 'cta' => null, 'suggestions' => []]);
    exit;
}

$result = guide_match($message);
echo json_encode(['ok' => true] + $result);
