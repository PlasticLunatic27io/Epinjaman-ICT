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
$pemohonTel = $rowpemohon['pemohon_no_tel'];
$pemohonPost = $rowpemohon['pemohon_status'];
$pemohonDept = $rowpemohon['pemohon_dept'];


// $pdf = new FPDF('P','mm','A4');
$pdf = new PDF_MC_Table('P','mm','A4');
// A4 = 210mm × 297mm


$pdf->SetTitle('JTM.PS.02.03-01');
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

$pdf->Image('favicon.png', 18, 10, 38); // x=20, y=25, width=20, height proportional

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'PERAKUAN PENERIMAAN PERALATAN ICT', 0, 0, 'C'); 
$pdf->Ln(4);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(190, 10, 'SECARA PINJAMAN', 0, 0, 'C'); 

$pdf->Ln(13);
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(18, 40);
$pdf->Cell(190, 5, 'Bahawa saya penerima dibawah, dengan ini mengakui telah menerima peralatan ICT seperti berikut:-', 0, 0, 'L');

$pdf->Ln(8);
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(18, 50);
$pdf->Cell(2, 3, '', 0, 0, 'C');
$pdf->Cell(15, 5, 'Tujuan', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(149, 5, $rowborrowing['purpose'], 'B', 0, 'L');

$pdf->Ln(5);
$pdf->SetXY(18, 60);
$pdf->Cell(2, 3, '', 0, 0, 'C');
$pdf->Cell(15, 5, 'Lokasi', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(73, 5, $rowborrowing['location'], 'B', 0, 'L');

$datetimestart = $rowborrowing['start_date'];
list($datestart, $timestart) = explode(' ', $datetimestart); 
$borrowingDateStart = $datestart;
$borrowingTimeStart = $timestart;

$pdf->Cell(10, 5, 'Tarikh', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(25, 5, $borrowingDateStart, 'B', 0, 'C');
$pdf->Cell(10, 5, 'Masa', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(25, 5, $borrowingTimeStart, 'B', 0, 'C');

$pdf->Ln(8);
$pdf->SetXY(18, 67);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(190, 5, 'BUTIRAN PERALATAN ICT', 0, 0, 'L');

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(211, 211, 211); 
$pdf->SetTextColor(0, 0, 0); 
$pdf->SetXY(18, 75);
$pdf->Cell(10, 5, 'BIL', 1, 0, 'C',true);
$pdf->Cell(80, 5, 'JENIS PERALATAN ICT', 1, 0, 'C',true);
$pdf->Cell(40, 5, 'NO SIRI', 1, 0, 'C',true);
$pdf->Cell(40, 5, 'NO TAG ASET', 1, 1, 'C',true);

$pdf->SetTextColor(0, 0, 0); 
$pdf->SetFont('Arial', '', 8);

$query2 = mysqli_query($conn, "SELECT * FROM borrowing_item WHERE borrow_id = '$borrowid'") or die(mysqli_error($conn));

$data = [];
while ($rowitem = mysqli_fetch_assoc($query2)) {
    $itemtag = $rowitem['item_tag'];
    $query4 = mysqli_query($conn, "SELECT * FROM item WHERE item_tag = '$itemtag'") or die(mysqli_error($conn));
    $row= mysqli_fetch_array($query4);
        $data[] = [$row['item_name'], $row['item_siri'], $itemtag];
}
$totalRows = 5;
while (count($data) < $totalRows) {
    $data[] = ['', '', '']; // Use empty values to ensure placeholder rows have no unintended data
}

$kira = 0;
$Yaxis = 73;
foreach ($data as $row) {
    $kira++;
    $Yaxis = $Yaxis + 7;
    // Check if the row has actual data or is a placeholder
    $itemName = $row[0] !== '' ? $row[0] : ''; // Display nothing if empty
    $itemSiri = $row[1] !== '' ? $row[1] : ''; // Display nothing if empty
    $itemTag = $row[2] !== '' ? $row[2] : ''; // Display nothing if empty
    $pdf->SetXY(18, $Yaxis);
    $pdf->Cell(10, 7, $kira, 1, 0, 'C'); // Row number
    $pdf->Cell(80, 7, '     '.$itemName, 1, 0, 'L'); // Item tag and name
    $pdf->Cell(40, 7, $itemSiri, 1, 0, 'C'); // Quantity
    $pdf->Cell(40, 7, $itemTag, 1, 1, 'C'); // Quantity
}

$pdf->Ln(2);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(10, 5,"", 0, 0);
$pdf->SetXY(18, 120);
$pdf->Cell(60, 5,"*Sila gunakan salinan yang baharu sekiranya ruang tidak mencukupi", 0, 0);

$pdf->Ln(2);
$pdf->Image('akuan1.png', 10, 130, 187);


$pdf->AddPage();
$pdf->Image('akuan2.png', 15, 50, 170);


$pdf->Output('I', 'Test.pdf');
?>
