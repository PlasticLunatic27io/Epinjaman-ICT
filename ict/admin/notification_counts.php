<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

if (empty($_SESSION['id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorised']);
    exit;
}

require_once __DIR__ . '/../dbcon.php';
require_once __DIR__ . '/admin_notification_service.php';

try {
    $conn = dbcon();
    $accountCheck = $conn->prepare('SELECT 1 FROM user WHERE user_id = ? LIMIT 1');
    $adminId = (int)$_SESSION['id'];
    $accountCheck->bind_param('i', $adminId);
    $accountCheck->execute();
    if (!$accountCheck->get_result()->fetch_row()) {
        $_SESSION = [];
        session_destroy();
        http_response_code(401);
        echo json_encode(['error' => 'Account removed']);
        exit;
    }
    echo json_encode(adminNotificationCounts($conn, (int)$_SESSION['id']));
} catch (Throwable $e) {
    error_log('Notification refresh: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Unable to refresh notifications']);
}
