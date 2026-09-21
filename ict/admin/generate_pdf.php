<?php
require_once('tcpdf/tcpdf.php'); // Include the TCPDF library

// Collect form data
$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

// Create a new PDF document
$pdf = new TCPDF();

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Form Data PDF');
$pdf->SetSubject('Generated PDF');
$pdf->SetKeywords('TCPDF, PDF, PHP');

// Set default header data
$pdf->SetHeaderData('', 0, 'Form Data', 'Generated with PHP');

// Set margins
$pdf->SetMargins(15, 27, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 12);

// Add content
$html = "
    <h1>Form Data</h1>
    <p><strong>Name:</strong> $name</p>
    <p><strong>Email:</strong> $email</p>
    <p><strong>Message:</strong> $message</p>
";

$pdf->writeHTML($html, true, false, true, false, '');

// Output the PDF as a download
$pdf->Output('form_data.pdf', 'D');
?>
