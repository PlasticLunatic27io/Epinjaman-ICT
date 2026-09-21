<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check sama ada session ID wujud
if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '')) {
    header("location:../login_form.php");
    exit();
}

$session_id = $_SESSION['id'];
$session_role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// SMART QUERY: Cek role untuk tarik nama username/display name
if ($session_role == 'admin') {
    // Kalau Admin, tarik dari table user
    $user_query = mysqli_query($conn, "SELECT * FROM user WHERE user_id = '$session_id'") or die(mysqli_error($conn));
    $user_row = mysqli_fetch_array($user_query);
    $admin_username = $user_row['username'];
} else {
    // Kalau Pemohon, tarik dari table pemohon
    $user_query = mysqli_query($conn, "SELECT * FROM pemohon WHERE pemohon_id = '$session_id'") or die(mysqli_error($conn));
    $user_row = mysqli_fetch_array($user_query);
    // Kita setkan admin_username kepada nama pemohon supaya script print tak error
    $admin_username = isset($user_row['pemohon_name']) ? $user_row['pemohon_name'] : 'User';
}
?>