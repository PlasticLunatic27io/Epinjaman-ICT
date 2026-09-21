<?php
require_once __DIR__.'/dbcon.php';require_once __DIR__.'/approval_workflow.php';$conn=dbcon();
$portal=(string)($_GET['portal']??'');
if($portal==='approver'){require __DIR__.'/approver/bootstrap.php';$account=requireApprover($conn);}
else{session_start();if(empty($_SESSION['id'])||!in_array($_SESSION['role']??'',['admin','pemohon'],true)){header('Location: login_form.php');exit;}}
$approvalId=filter_var($_GET['approval']??'',FILTER_VALIDATE_INT);$borrowId=filter_var($_GET['id']??'',FILTER_VALIDATE_INT);
$r=$approvalId?wfRow($conn,'SELECT * FROM borrowing_approvals WHERE approval_id=?','i',[$approvalId]):($borrowId?wfLatest($conn,$borrowId):null);
if(!$r){http_response_code(404);exit('Document not found.');}
$allowed=false;
if($portal==='approver'){$allowed=(int)$r['approver_id']===(int)$account['approver_id'];}
elseif(($_SESSION['role']??'')==='admin'){$allowed=(bool)wfRow($conn,'SELECT user_id FROM user WHERE user_id=?','i',[(int)$_SESSION['id']]);}
elseif(($_SESSION['role']??'')==='pemohon'){$s=json_decode($r['snapshot_json'],true);$allowed=(int)$s['applicant_id']===(int)$_SESSION['id'];}
if(!$allowed){http_response_code(404);exit('Document not found.');}
if(!$r['pdf_filename']){http_response_code(409);exit('The signed approval PDF is not available yet.');}
if(!$approvalId&&$r['state']!=='approved'){http_response_code(409);exit('Current department approval is required.');}
if(!$approvalId && !$r['handed_over_at'] && !hash_equals($r['snapshot_hash'],wfDigest(wfSnapshot($conn,(int)$r['borrow_id'])))){http_response_code(409);exit('Request details changed. Fresh department approval is required.');}
$path=wfPdfPath($r['pdf_filename']);if(!is_file($path)){http_response_code(404);exit('Document unavailable. Please contact JTM.');}
header('Content-Type: application/pdf');header('X-Content-Type-Options: nosniff');header('Cache-Control: private,no-store');header('Content-Disposition: inline; filename="Approval_Request_'.(int)$r['borrow_id'].'.pdf"');header('Content-Length: '.filesize($path));readfile($path);
