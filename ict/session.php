<?php
 session_start();
 // Semak jika session ID tidak wujud, maksudnya user belum login
 if (!isset($_SESSION['id']) || (trim($_SESSION['id']) == '')) { ?>
    <script>
        window.location = "loginpage.php";
    </script>
<?php
    exit();
}

// Simpan ID sesi ke dalam variable untuk kegunaan query di page lain
$session_id = $_SESSION['id'];
?>