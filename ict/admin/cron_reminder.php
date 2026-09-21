<?php
require_once __DIR__ . "/../dbcon.php";
$conn = dbcon();

require_once '../PHPMailer/src/PHPMailer.php';
require_once '../PHPMailer/src/SMTP.php';
require_once '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Cari permohonan yang tarikh pulangnya adalah ESOK
$tomorrow = date('Y-m-d', strtotime('+1 day'));

$query = mysqli_query($conn, "SELECT b.*, p.pemohon_name, p.pemohon_email 
                              FROM borrowing b 
                              JOIN pemohon p ON b.pemohon_college_id = p.pemohon_college_id 
                              WHERE DATE(b.end_date) = '$tomorrow' AND b.status = 'Lulus'");

while($data = mysqli_fetch_array($query)){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'purekptmbp@gmail.com';
        $mail->Password   = 'rqhp pxro ucjz btyd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('purekptmbp@gmail.com', 'ePinjamICT Reminder');
        $mail->addAddress($data['pemohon_email'], $data['pemohon_name']);
        $mail->addAddress('dnshqll6269@gmail.com', 'Admin Reminder');

        $mail->isHTML(true);
        $mail->Subject = 'PERINGATAN: Pemulangan Item Esok! (#' . $data['borrow_id'] . ')';
        $mail->Body = "
            <div style='font-family: sans-serif; border: 1px solid #fee2e2; padding: 20px; border-radius: 10px;'>
                <h3 style='color: #ef4444;'>Peringatan Pemulangan Item</h3>
                <p>Hi <b>" . $data['pemohon_name'] . "</b>,</p>
                <p>Ini adalah peringatan mesra bahawa item pinjaman anda (#" . $data['borrow_id'] . ") perlu dipulangkan <b>ESOK</b> pada tarikh <b>" . date('d-m-Y', strtotime($data['end_date'])) . "</b>.</p>
                <p>Terima kasih.</p>
            </div>";
        $mail->send();
    } catch (Exception $e) {
        error_log("Reminder failed for Borrow ID: " . $data['borrow_id']);
    }
}
?>