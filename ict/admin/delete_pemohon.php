<?php
require_once __DIR__ . "/../dbcon.php";
$conn = dbcon();
if (isset($_POST['delete_pemohon'])){
$id=$_POST['selector'];
$N = count($id);
for($i=0; $i < $N; $i++)
{
	$result = mysqli_query($conn,"DELETE FROM pemohon where pemohon_id='$id[$i]'");
}
header("location: pemohon.php");
}
?>