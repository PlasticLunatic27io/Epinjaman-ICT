<?php
require_once __DIR__ . "/../workflow_print_gate.php";

//require('fpdf186/fpdf.php');
require('fpdf186/mc_table.php');
require_once __DIR__ . "/../dbcon.php"; 
$conn = dbcon(); 

$get_id = $_GET['id'];
$borrowid = $get_id;

$query = mysqli_query($conn,"select * from borrowing where borrow_id= '$borrowid'")or die(mysqli_error($conn));
$rowborrowing = mysqli_fetch_array($query);

$pemohon_college_id = $rowborrowing['pemohon_college_id'];

$query3 = mysqli_query($conn,"select * from pemohon where pemohon_college_id= '$pemohon_college_id'")or die(mysqli_error($conn));
$rowpemohon = mysqli_fetch_array($query3);

$pemohonName = $rowpemohon['pemohon_name'];
$pemohonId = $rowpemohon['pemohon_college_id'];
$pemohonTel = $rowpemohon['pemohon_no_tel'];
$pemohonPost = $rowpemohon['pemohon_status'];
$pemohonDept = $rowpemohon['pemohon_dept'];


// $pdf = new FPDF('P','mm','A4');
$pdf = new PDF_MC_Table('P','mm','A4');
// A4 = 210mm × 297mm


$pdf->SetTitle('JTM.PS.02.02-00');
$pdf->SetAutoPageBreak(true, 10);


$pdf->AddPage();

$pdf->SetFont('Arial', '', 8);
$text = '   JTM.PS.02.02-00   ';
$textWidth = $pdf->GetStringWidth($text) + 2;
$pageWidth = $pdf->GetPageWidth(); // Set the position to right
$rightMargin = 10;
$xPosition = $pageWidth - $textWidth - $rightMargin;
$pdf->SetXY($xPosition, 10);
$pdf->Cell($textWidth, 5, $text, 1, 1, 'C');

$pdf->Ln(2);

$pdf->Image('favicon.png', 10, 10, 38); // x=20, y=25, width=20, height proportional
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(40, 18); // Move to position after the image
$pdf->Cell(140, 10, 'BORANG PERMOHONAN PINJAMAN PERALATAN ICT', 0, 0, 'C'); // Centered text with a border

$pdf->Ln(13);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(0, 0, 0); // Set fill color to black
$pdf->SetTextColor(255, 255, 255); // RGB values for white
$pdf->Cell(190, 5, 'MAKLUMAT PEMINJAMAN', 0, 0, 'C', true);

$borrowingDalaman = "";
$borrowingLuaran ="";

$borrowingType = $rowborrowing['type'];

if($borrowingType == 'Dalaman'){
    $borrowingDalaman = "/";
}else if($borrowingType == 'Luaran'){
    $borrowingLuaran = "/";
};


$pdf->Ln(8);
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(0, 0, 0); 
$pdf->Cell(105, 5, 'SILA LENGKAPKAN BAHAGIAN A, B DAN C', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(35, 5, 'JENIS PINJAMAN', 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(16, 5, 'Dalaman', 1, 0, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(9, 5, $borrowingDalaman, 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(16, 5, 'Luaran', 1, 0, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(9, 5, $borrowingLuaran, 1, 0, 'C');

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(190, 5, 'A. Maklumat Peminjam', 0, 0, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Nama', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, $pemohonName, 'B', 0, 'L');

$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'No. ID Staff / ID Pelajar', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, $pemohonId , 'B', 0, 'L');

$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'No.Tel', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, $pemohonTel , 'B', 0, 'L');

$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Jawatan', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, $pemohonPost, 'B', 0, 'L');

$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Unit/Bahagian', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, $pemohonDept, 'B', 0, 'L');


$borrowingPurpose = $rowborrowing['purpose'];
$borrowingStartDate = $rowborrowing['start_date'];

$datetimestart = $rowborrowing['start_date'];
list($datestart, $timestart) = explode(' ', $datetimestart); 
$borrowingDateStart = $datestart;
$borrowingTimeStart = $timestart;

$datetimeend = $rowborrowing['end_date'];
list($dateend, $timeend) = explode(' ', $datetimeend); 
$borrowingDateEnd= $dateend;
$borrowingTimeEnd = $timeend;

$borrowingLocation = $rowborrowing['location'];
$borrowingTech = $rowborrowing['need_technician'];


$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(190, 5, 'A. Maklumat Tempahan', 0, 0, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Tujuan Tempahan', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, $borrowingPurpose, 'B', 0, 'L');

$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Tarikh Tempahan', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, 'Dari : '. $borrowingDateStart . ' Hingga : ' . $borrowingDateEnd, 'B', 0, 'L');

$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Masa', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, 'Dari : '. $borrowingTimeStart . ' Hingga : ' . $borrowingTimeEnd, 'B', 0, 'L');

$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Lokasi penggunaan', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, $borrowingLocation, 'B', 0, 'L');
$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Perlu Juruteknik', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, $borrowingTech, 'B', 0, 'L');

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(0, 0, 0); // Set fill color to black
$pdf->SetTextColor(255, 255, 255); // RGB values for white
$pdf->Cell(10, 5, 'BIL', 1, 0, 'C', true);
$pdf->Cell(150, 5, 'NAMA PERALATAN/MODEL/KEPERLUAN', 1, 0, 'C', true);
$pdf->Cell(30, 5, 'BILANGAN', 1, 1, 'C', true);


$pdf->SetTextColor(0, 0, 0); 
$pdf->SetFont('Arial', '', 8);


$query2 = mysqli_query($conn, "SELECT * FROM borrowing_item WHERE borrow_id = '$borrowid'") or die(mysqli_error($conn));

$data = [];
while ($rowitem = mysqli_fetch_assoc($query2)) {
    $itemtag = $rowitem['item_tag'];
    $query4 = mysqli_query($conn, "SELECT * FROM item WHERE item_tag = '$itemtag'") or die(mysqli_error($conn));
    $row= mysqli_fetch_array($query4);
    
    $data[] = [$itemtag, $row['item_name'], $rowitem['item_qty']];
}
$totalRows = 9;
while (count($data) < $totalRows) {
    $data[] = ['', '', '']; // Use empty values to ensure placeholder rows have no unintended data
}

$kira = 0;
foreach ($data as $row) {
    $kira++;

    // Check if the row has actual data or is a placeholder
    $itemTag = $row[0] !== '' ? $row[0] : ''; // Display nothing if empty
    $itemName = $row[1] !== '' ? $row[1] : ''; // Display nothing if empty
    $itemQty = $row[2] !== '' ? $row[2] : ''; // Display nothing if empty

    $pdf->Cell(10, 7, $kira, 1, 0, 'C'); // Row number
    $pdf->Cell(150, 7, $itemTag . '    ' . $itemName, 1, 0, 'L'); // Item tag and name
    $pdf->Cell(30, 7, $itemQty, 1, 1, 'C'); // Quantity
}

$pdf->Ln(2);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(10, 5,"", 0, 0);
$pdf->Cell(60, 5,"*Sila sediakan lampiran jika ruang tidak mencukupi", 0, 0);

$pdf->Ln(2);
$pdf->Image('TTBorang.png', 10, 188, 190);

$pdf->Output('I', 'Test.pdf');
?>
