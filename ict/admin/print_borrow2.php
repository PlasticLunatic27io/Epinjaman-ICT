<?php
require_once __DIR__ . "/../workflow_print_gate.php";

require_once __DIR__ . "/../dbcon.php"; 
$conn = dbcon(); 

$get_id = $_GET['id'];
$borrowid = $get_id;

// Fetch borrowing data
$query = mysqli_query($conn,"select * from borrowing where borrow_id= '$borrowid'")or die(mysqli_error($conn));
$rowborrowing = mysqli_fetch_array($query);

if (!$rowborrowing) {
    die("Borrowing record not found");
}

$pemohon_college_id = $rowborrowing['pemohon_college_id'];

// Fetch pemohon data
$query3 = mysqli_query($conn,"select * from pemohon where pemohon_college_id= '$pemohon_college_id'")or die(mysqli_error($conn));
$rowpemohon = mysqli_fetch_array($query3);

if (!$rowpemohon) {
    die("Pemohon record not found");
}

$pemohonName = $rowpemohon['pemohon_name'];
$pemohonId = $rowpemohon['pemohon_college_id'];
$pemohonTel = $rowpemohon['pemohon_no_tel'];
$pemohonPost = $rowpemohon['pemohon_status'];
$pemohonDept = $rowpemohon['pemohon_dept'];

// Borrowing details
$borrowingPurpose = $rowborrowing['purpose'];

$datetimestart = $rowborrowing['start_date'];
list($datestart, $timestart) = explode(' ', $datetimestart); 
$borrowingDateStart = $datestart;
$borrowingTimeStart = $timestart;

$datetimeend = $rowborrowing['end_date'];
list($dateend, $timeend) = explode(' ', $datetimeend); 
$borrowingDateEnd = $dateend;
$borrowingTimeEnd = $timeend;

$borrowingLocation = $rowborrowing['location'];

// Fetch equipment items with item_siri from borrowing_item
$query2 = mysqli_query($conn, "SELECT * FROM borrowing_item WHERE borrow_id = '$borrowid'") or die(mysqli_error($conn));

$equipmentList = [];
while ($rowitem = mysqli_fetch_assoc($query2)) {
    $itemtag = $rowitem['item_tag'];
    
    // Get item name from item table
    $query4 = mysqli_query($conn, "SELECT item_name FROM item WHERE item_tag = '$itemtag'") or die(mysqli_error($conn));
    $row = mysqli_fetch_array($query4);
    
    $equipmentList[] = [
        'name' => $row['item_name'] ?? '',
        'serial_no' => $rowitem['item_siri'] ?? '',  // Get from borrowing_item table!
        'tagging_no' => $itemtag
    ];
}

// Prepare data for Python script
$formData = [
    'nama' => $pemohonName,
    'no_id_staf' => $pemohonId,
    'no_tel' => $pemohonTel,
    'jawatan' => $pemohonPost,
    'unit_bahagian' => $pemohonDept,
    'tujuan_tempahan' => $borrowingPurpose,
    'tarikh_dari' => $borrowingDateStart,
    'tarikh_hingga' => $borrowingDateEnd,
    'masa_dari' => $borrowingTimeStart,
    'masa_hingga' => $borrowingTimeEnd,
    'lokasi_penggunaan' => $borrowingLocation,
    'equipment' => $equipmentList
];

// Save to temporary JSON file
$tempDir = sys_get_temp_dir();
$jsonFile = $tempDir . '/form_data_' . $borrowid . '.json';
file_put_contents($jsonFile, json_encode($formData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Paths
$pdfTemplate = __DIR__ . '/jtm.pdf';  // Your template PDF
$pythonScript = __DIR__ . '/fill_jtm_overlay.py';  // Python script
$outputPdf = $tempDir . '/filled_form_' . $borrowid . '.pdf';

// Check if template exists
if (!file_exists($pdfTemplate)) {
    die("Template PDF not found: $pdfTemplate");
}

// Check if Python script exists
if (!file_exists($pythonScript)) {
    die("Python script not found: $pythonScript");
}

// Use an installed interpreter rather than the Windows Store alias.
$pythonExecutable = getenv('ICT_PYTHON_EXECUTABLE') ?: 'C:/Users/User/.cache/codex-runtimes/codex-primary-runtime/dependencies/python/python.exe';
if (!is_file($pythonExecutable)) {
    @unlink($jsonFile);
    die('PDF generation runtime unavailable. Configure ICT_PYTHON_EXECUTABLE with the full Python executable path.');
}
// Quote each argument once; quoting the complete command corrupts Windows paths.
$command = escapeshellarg($pythonExecutable) . ' -X utf8 ' .
           escapeshellarg($pythonScript) . ' ' .
           escapeshellarg($pdfTemplate) . ' ' .
           escapeshellarg($jsonFile) . ' ' .
           escapeshellarg($outputPdf) . ' 2>&1';
// A previous output must never be mistaken for a successful new generation.
if (file_exists($outputPdf) && !unlink($outputPdf)) {
    @unlink($jsonFile);
    die('Unable to replace the previous generated PDF.');
}
$output = [];
$exitCode = 1;
exec($command, $output, $exitCode);
$output = implode(PHP_EOL, $output);
clearstatcache(true, $outputPdf);

// Check if PDF was created successfully
if ($exitCode === 0 && is_file($outputPdf) && filesize($outputPdf) > 0) {
    // Serve the PDF for download
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="Borang_Pinjaman_' . $borrowid . '.pdf"');
    header('Content-Length: ' . filesize($outputPdf));
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');
    
    readfile($outputPdf);
    
    // Clean up temporary files
    @unlink($jsonFile);
    @unlink($outputPdf);
} else {
    echo "<h2>Error generating PDF</h2>";
    echo "<p>Command: " . htmlspecialchars($command) . "</p>";
    echo "<pre>" . htmlspecialchars($output) . "</pre>";
    echo "<p>Template: " . htmlspecialchars($pdfTemplate) . "</p>";
    echo "<p>Python script: " . htmlspecialchars($pythonScript) . "</p>";
}

mysqli_close($conn);
?>
