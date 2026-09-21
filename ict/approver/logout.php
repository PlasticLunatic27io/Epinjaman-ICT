<?php
require __DIR__ . '/bootstrap.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);header('Allow: POST');exit;}
try{approverCsrf();}catch(InvalidArgumentException $e){http_response_code(403);exit('Invalid request.');}
$_SESSION=[];session_destroy();setcookie(session_name(),'', ['expires'=>time()-3600,'path'=>'/ict/approver/','httponly'=>true,'samesite'=>'Lax','secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off']);header('Location: login.php');
