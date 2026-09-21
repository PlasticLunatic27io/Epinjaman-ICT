<?php
//require('fpdf186/fpdf.php');
require('fpdf186/mc_table.php');


$pdfName = $_POST['pdfName'];
$pdfAddress = $_POST['pdfAddress'];
$pdfAddress2 = $_POST['pdfAddress2'];
$pdfCity = $_POST['pdfCity'];
$pdfState = $_POST['pdfState'];
$pdfZip = $_POST['pdfZip'];
$pdfCountry = $_POST['pdfCountry'];
$pdfPhone = $_POST['pdfPhone'];
$pdfEmail = $_POST['pdfEmail'];

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

$pdf->Ln(8);
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(0, 0, 0); 
$pdf->Cell(105, 5, 'SILA LENGKAPKAN BAHAGIAN A, B DAN C', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(35, 5, 'JENIS PINJAMAN', 1, 0, 'C');
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(16, 5, 'Dalaman', 1, 0, 'C');
$pdf->Cell(9, 5, '', 1, 0, 'C');
$pdf->Cell(16, 5, 'Luaran', 1, 0, 'C');
$pdf->Cell(9, 5, '', 1, 0, 'C');

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(190, 5, 'A. Maklumat Peminjam', 0, 0, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Nama', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, $pdfName, 'B', 0, 'L');

$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'No.Tel', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, $pdfPhone , 'B', 0, 'L');

$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Jawatan', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, '', 'B', 0, 'L');

$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Unit/Bahagian', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, '', 'B', 0, 'L');

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(190, 5, 'A. Maklumat Tempahan', 0, 0, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Tujuan Tempahan', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, '', 'B', 0, 'L');

$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Tarikh Tempahan', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, '', 'B', 0, 'L');

$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Masa', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, '', 'B', 0, 'L');

$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Lokasi penggunaan', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, '', 'B', 0, 'L');
$pdf->Ln(5);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Perlu Juruteknik', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, '', 'B', 0, 'L');

$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetFillColor(0, 0, 0); // Set fill color to black
$pdf->SetTextColor(255, 255, 255); // RGB values for white
$pdf->Cell(10, 5, 'BIL', 1, 0, 'C', true);
$pdf->Cell(150, 5, 'NAMA PERALATAN/MODEL/KEPERLUAN', 1, 0, 'C', true);
$pdf->Cell(30, 5, 'BILANGAN', 1, 1, 'C', true);


$pdf->SetTextColor(0, 0, 0); 
$pdf->SetFont('Arial', '', 8);

$data = [
    ['1', 'Laptop ZBook HP', '1'],
    ['2', 'Projector', '1'],
    ['3', 'Keyboard', '1'],
    ['4', '', ''],
    ['5', '', ''],
    ['6', '', ''],
    ['7', '', ''],
    ['8', '', ''],
    ['9', '', '']
];

foreach ($data as $row) {
    $pdf->Cell(10, 7, $row[0], 1, 0, 'C');
    $pdf->Cell(150, 7, $row[1], 1, 0, 'L');
    $pdf->Cell(30, 7, $row[2], 1, 1, 'C');
}

$pdf->Ln(2);
$pdf->SetFont('Arial','I',8);
$pdf->Cell(10, 5,"", 0, 0);
$pdf->Cell(60, 5,"*Sila sediakan lampiran jika ruang tidak mencukupi", 0, 0);

$pdf->Ln(2);
$pdf->Image('TTBorang.png', 10, 188, 190);

$pdf->Output('I', 'Test.pdf');
?>
