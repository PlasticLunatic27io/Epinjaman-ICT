<?php
require_once __DIR__ . "/../dbcon.php";
$conn = dbcon();

// Nama fail dengan tarikh hari ini
$filename = "Laporan_Pelepasan_ICT_" . date('Y-m-d') . ".xls";

// Header untuk paksa browser download sebagai Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

?>
<style>
    .header { background-color: #1E3A8A; color: white; font-weight: bold; }
    .category-title { background-color: #F3F4F6; font-weight: bold; text-align: center; }
</style>

<table border="1">
    <thead>
        <tr class="header">
            <th>App ID</th>
            <th>Applicant Name</th>
            <th>ID Number</th>
            <th>Category</th>
            <th>Items Borrowed</th>
            <th>Purpose</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Approved By</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Kita tarik data dan susun ikut Kategori (Type)
        $query = mysqli_query($conn, "SELECT b.*, p.pemohon_name 
                                     FROM borrowing b 
                                     INNER JOIN pemohon p ON b.pemohon_college_id = p.pemohon_college_id 
                                     WHERE b.status='Lulus' AND (NOT EXISTS (SELECT 1 FROM borrowing_approvals w WHERE w.borrow_id=b.borrow_id) OR EXISTS (SELECT 1 FROM borrowing_approvals w WHERE w.borrow_id=b.borrow_id AND w.handed_over_at IS NOT NULL)) 
                                     ORDER BY b.type ASC, b.approved_date DESC");

        if(mysqli_num_rows($query) > 0) {
            $current_cat = "";
            while($row = mysqli_fetch_array($query)) {
                // Tambah baris pemisah kalau kategori bertukar
                if($current_cat != $row['type']) {
                    $current_cat = $row['type'];
                    echo "<tr><td colspan='9' class='category-title'>KATEGORI: " . strtoupper($current_cat) . "</td></tr>";
                }
                ?>
                <tr>
                    <td><?php echo $row['borrow_id']; ?></td>
                    <td><?php echo $row['pemohon_name']; ?></td>
                    <td><?php echo $row['pemohon_college_id']; ?></td>
                    <td><?php echo $row['type']; ?></td>
                    <td><?php echo $row['borrow_item']; ?></td>
                    <td><?php echo $row['purpose']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['start_date'])); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['end_date'])); ?></td>
                    <td><?php echo $row['user_name']; ?></td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='9'>Tiada rekod dijumpai.</td></tr>";
        }
        ?>
    </tbody>
</table>