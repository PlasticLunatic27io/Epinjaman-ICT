<?php
// Serve approver documents under the same path as the protected approver session cookie.
$_GET['portal']='approver';
require __DIR__.'/../approval_pdf.php';
