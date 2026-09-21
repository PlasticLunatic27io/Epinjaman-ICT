<?php
require_once __DIR__ . "/../dbcon.php";
$conn = dbcon();

// Set header supaya browser kenal ini adalah file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Returned_Items_Report_" . date('Y-m-d') . ".xls");
header("Pragma: no-cache");
header("Expires: 0");

?>
<table border="1">
    <thead>
        <tr style="background-color: #1E3A8A; color: white;">
            <th>Ref ID</th>
            <th>Personnel Name</th>
            <th>Personnel ID</th>
            <th>Item Name</th>
            <th>Item Tag</th>
            <th>Condition Note</th>
            <th>Date Returned</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query_string = "SELECT * FROM borrowing_item WHERE status='Returned' ORDER BY return_date DESC";
        $result = mysqli_query($conn, $query_string);

        while($row = mysqli_fetch_array($result)){
            $item_tag = $row['item_tag'];
            $pemohon_id = $row['pemohon_college_id'];

            // Get Item Info
            $item_query = mysqli_query($conn, "SELECT item_name FROM item WHERE item_tag = '$item_tag'");
            $item_info = mysqli_fetch_assoc($item_query);

            // Get Pemohon Info
            $pemohon_query = mysqli_query($conn, "SELECT pemohon_name FROM pemohon WHERE pemohon_college_id = '$pemohon_id'");
            $pemohon_info = mysqli_fetch_assoc($pemohon_query);
        ?>
        <tr>
            <td><?php echo $row['borrow_id']; ?></td>
            <td><?php echo $pemohon_info['pemohon_name']; ?></td>
            <td><?php echo $row['pemohon_college_id']; ?></td>
            <td><?php echo $item_info['item_name']; ?></td>
            <td><?php echo $row['item_tag']; ?></td>
            <td><?php echo $row['notes']; ?></td>
            <td><?php echo date('d/m/Y h:i A', strtotime($row['return_date'])); ?></td>
            <td><?php echo $row['status']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>