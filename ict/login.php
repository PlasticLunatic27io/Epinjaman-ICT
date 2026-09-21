<?php
require_once __DIR__ . "/dbcon.php";
$conn = dbcon();
?>
<?php
session_start();    
 

// Panggil fungsi dbcon() dan simpan ke dalam $conn


if(isset($_POST['login']))
{
    // Sekarang $conn sudah wujud dan boleh digunakan
    $username = mysqli_real_escape_string($conn, $_POST['txtUserName']);
    $password = mysqli_real_escape_string($conn, $_POST['txtPassword']);

    // ... kod seterusnya kekal sama

    // 1. Semak dalam jadual 'user' (Admin)
    $query_admin = "SELECT * FROM user WHERE username='$username' AND password='$password'";
    $result_admin = mysqli_query($conn, $query_admin) or die(mysqli_error($conn));
    $row_admin = mysqli_fetch_array($result_admin);
    $num_row_admin = mysqli_num_rows($result_admin);

    if($num_row_admin > 0){
        // Jika Admin: Ke admin/dashboard.php
        $_SESSION['id'] = $row_admin['user_id'];
        $_SESSION['role'] = 'admin';
        header("location:admin/dashboard.php");
        exit();
    } 
    else {
        // 2. Jika bukan Admin: Semak dalam jadual 'pemohon' (Staff/Pelajar)
        // Menggunakan pemohon_college_id sebagai username
        $query_pemohon = "SELECT * FROM pemohon WHERE pemohon_college_id='$username' AND password='$password'";
        $result_pemohon = mysqli_query($conn, $query_pemohon) or die(mysqli_error($conn));
        $row_pemohon = mysqli_fetch_array($result_pemohon);
        $num_row_pemohon = mysqli_num_rows($result_pemohon);

        if($num_row_pemohon > 0){
            // Jika Pemohon: Ke dashboard.php (luar folder admin)
            $_SESSION['id'] = $row_pemohon['pemohon_id'];
            $_SESSION['role'] = 'pemohon';
            $_SESSION['name'] = $row_pemohon['pemohon_name'];
            header("location:dashboard.php");
            exit();
        } 
        else {
            // Jika kedua-duanya salah
            echo "<script>alert('ID atau Password Salah!'); window.location='login.php';</script>";
            exit();
        }
    }
}
?>