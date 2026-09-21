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

$pdf->SetFont('Arial', 'B', 10);
$text = ' JTM.PS.02.02-00 ';
$textWidth = $pdf->GetStringWidth($text) + 2;
$pageWidth = $pdf->GetPageWidth(); // Set the position to right
$rightMargin = 10;
$xPosition = $pageWidth - $textWidth - $rightMargin;
$pdf->SetXY($xPosition, 10);
$pdf->Cell($textWidth, 5, $text, 1, 1, 'C');

$pdf->Ln(4);

$pdf->Image('favicon.png', 10, 20, 30); // x=20, y=25, width=20, height proportional
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(40, 25); // Move to position after the image
$pdf->Cell(140, 10, 'BORANG PERMOHONAN PINJAMAN PERALATAN ICT', 0, 0, 'C'); // Centered text with a border

$pdf->Ln(13);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(0, 0, 0); // Set fill color to black
$pdf->SetTextColor(255, 255, 255); // RGB values for white
$pdf->Cell(190, 5, 'MAKLUMAT PEMINJAMAN', 0, 0, 'C', true);

$pdf->Ln(8);
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0); 
$pdf->Cell(105, 5, 'SILA LENGKAPKAN BAHAGIAN A, B DAN C', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(35, 5, 'JENIS PINJAMAN', 1, 0, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(16, 5, 'Dalaman', 1, 0, 'C');
$pdf->Cell(9, 5, '', 1, 0, 'C');
$pdf->Cell(16, 5, 'Luaran', 1, 0, 'C');
$pdf->Cell(9, 5, '', 1, 0, 'C');

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 5, 'A. Maklumat Peminjam', 0, 0, 'L');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Nama', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, $pdfName, 'B', 0, 'L');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'No.Tel', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, $pdfPhone , 'B', 0, 'L');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Jawatan', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, '', 'B', 0, 'L');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Unit/Bahagian', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, '', 'B', 0, 'L');

$pdf->Ln(8);
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(190, 5, 'A. Maklumat Tempahan', 0, 0, 'L');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Tujuan Tempahan', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, '', 'B', 0, 'L');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Tarikh Tempahan', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, '', 'B', 0, 'L');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Masa', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, '', 'B', 0, 'L');

$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Lokasi penggunaan', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, '', 'B', 0, 'L');
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(15, 5, '', 0, 0, 'C');
$pdf->Cell(40, 5, 'Perlu Juruteknik', 0, 0, 'L');
$pdf->Cell(3, 5, ':', 0, 0, 'C');
$pdf->Cell(120, 5, '', 'B', 0, 'L');

$pdf->Ln(10);


$pdf->SetFont('Arial','B',16);
$pdf->Cell(28, 5,"Address2:", 0, 0);
$pdf->SetFont('Arial', '', 16);
$pdf->MultiCell(60, 5, $pdfAddress2, 0, "L");


$pdf->Ln(4);


$pdf->SetFont('Arial','B',16);
$pdf->Cell(13, 5,"City:", 0, 0);
$pdf->SetFont('Arial', '', 16);
$pdf->MultiCell(60, 5, $pdfCity, 0, "L");


$pdf->Ln(4);


$pdf->SetFont('Arial','B',16);
$pdf->Cell(16, 5,"State:", 0, 0);
$pdf->SetFont('Arial', '', 16);
$pdf->MultiCell(60, 5, $pdfState, 0, "L");


$pdf->Ln(4);


$pdf->SetFont('Arial','B',16);
$pdf->Cell(11, 5,"Zip:", 0, 0);
$pdf->SetFont('Arial', '', 16);
$pdf->MultiCell(40, 5, $pdfZip, 0, "L");


$pdf->Ln(4);


$pdf->SetFont('Arial','B',16);
$pdf->Cell(24, 5,"Country:", 0, 0);
$pdf->SetFont('Arial', '', 16);
$pdf->MultiCell(60, 5, $pdfCountry, 0, "L");


$pdf->Ln(4);


$pdf->SetFont('Arial','B',16);
$pdf->Cell(20, 5,"Phone:", 0, 0);
$pdf->SetFont('Arial', '', 16);
$pdf->MultiCell(60, 5, $pdfPhone, 0, "L");


$pdf->Ln(4);


$pdf->SetFont('Arial','B',16);
$pdf->Cell(18, 5,"Email:", 0, 0);
$pdf->SetFont('Arial', '', 16);
$pdf->MultiCell(80, 5, $pdfEmail, 0, "L");


$pdf->Ln(15);

//Table

$pdf->SetFont('Arial', 'B', 12);

// Table Header
$pdf->Cell(40, 10, 'ID', 0, 0, 'C');
$pdf->Cell(80, 10, 'Name', 1, 0, 'C');
$pdf->Cell(40, 10, 'Age', 1, 1, 'C'); // Last cell in row, so line break (1)

// Table Data
$pdf->SetFont('Arial', '', 12);
$data = [
    ['1', 'John Doe', '25'],
    ['2', 'Jane Smith', '30'],
    ['3', 'James Brown', '45']
];

foreach ($data as $row) {
    $pdf->Cell(40, 10, $row[0], 1, 0, 'C');
    $pdf->Cell(80, 10, $row[1], 1, 0, 'L');
    $pdf->Cell(40, 10, $row[2], 1, 1, 'C');
}

$pdf->Ln(15);

$pdf->SetWidths(array(12,71,25,40,42));
for($i = 1; $i <= 40; $i++) {
    $pdf->Row(array(1, 2, 3, 4, 5));
}


$pdf->Output('I', 'Test.pdf');
?>
