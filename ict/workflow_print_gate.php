<?php
// Keep the legacy print layouts for historical loans; managed loans use their frozen approval PDF.
if(session_status()!==PHP_SESSION_ACTIVE)session_start();
require_once __DIR__.'/dbcon.php';require_once __DIR__.'/approval_workflow.php';$conn=dbcon();
$gateId=filter_var($_GET['id']??'',FILTER_VALIDATE_INT);
if(!$gateId){http_response_code(404);exit('Request not found.');}
if(empty($_SESSION['id'])||!in_array($_SESSION['role']??'',['admin','pemohon'],true)){header('Location: ../login_form.php');exit;}
$gateBorrow=wfRow($conn,'SELECT pemohon_college_id FROM borrowing WHERE borrow_id=?','i',[$gateId]);if(!$gateBorrow){http_response_code(404);exit('Request not found.');}
if($_SESSION['role']==='admin'){$gateAllowed=(bool)wfRow($conn,'SELECT user_id FROM user WHERE user_id=?','i',[(int)$_SESSION['id']]);}
else{$p=wfRow($conn,'SELECT pemohon_college_id FROM pemohon WHERE pemohon_id=?','i',[(int)$_SESSION['id']]);$gateAllowed=$p&&$p['pemohon_college_id']===$gateBorrow['pemohon_college_id'];}
if(!$gateAllowed){http_response_code(404);exit('Request not found.');}
if(wfLatest($conn,$gateId)){header('Location: ../approval_pdf.php?id='.$gateId);exit;}
