<?php

function adminRequireActiveAccount(?mysqli $existingConnection = null): array {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $adminId = (int)($_SESSION['id'] ?? 0);
    $conn = $existingConnection;
    if (!$conn) {
        require_once __DIR__ . '/../dbcon.php';
        $conn = dbcon();
    }

    $account = null;
    if ($adminId > 0) {
        $stmt = $conn->prepare('SELECT * FROM user WHERE user_id = ? LIMIT 1');
        $stmt->bind_param('i', $adminId);
        $stmt->execute();
        $account = $stmt->get_result()->fetch_assoc();
    }

    if (!$account) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        $login = '../login_form.php?session=account_removed';
        if (!headers_sent()) {
            header('Location: ' . $login);
        } else {
            echo '<script>window.location.replace(' . json_encode($login) . ');</script>';
            echo '<noscript><meta http-equiv="refresh" content="0;url=' . htmlspecialchars($login, ENT_QUOTES, 'UTF-8') . '"></noscript>';
        }
        exit;
    }

    $_SESSION['role'] = 'admin';
    return $account;
}
