<?php
require_once __DIR__ . '/core.php';
require_once __DIR__ . '/../dbcon.php';
$conn = dbcon();
session_name('ICT_APPROVER');
session_set_cookie_params(['httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off','samesite'=>'Lax','path'=>'/ict/approver/']);
session_start();
$_SESSION['asset_csrf'] ??= bin2hex(random_bytes(32));
function requireApprover($conn) {
    $id = (int)($_SESSION['approver_id'] ?? 0);
    $read = $conn->prepare('SELECT a.* FROM approver_accounts a WHERE a.approver_id = ? AND a.password_hash IS NOT NULL AND EXISTS (SELECT 1 FROM department_approvers d WHERE d.approval_email = a.email AND d.is_active = 1)');
    $read->bind_param('i', $id); $read->execute();
    $account = $read->get_result()->fetch_assoc();
    if (!$account) { header('Location: login.php'); exit; }
    return $account;
}
