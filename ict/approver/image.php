<?php
require __DIR__ . '/bootstrap.php';$account=requireApprover($conn);
$id=filter_var($_GET['id']??'',FILTER_VALIDATE_INT);
if(!$id){http_response_code(404);exit;}
$q=$conn->prepare('SELECT filename FROM approver_assets WHERE asset_id = ? AND approver_id = ?');$q->bind_param('ii',$id,$account['approver_id']);$q->execute();$asset=$q->get_result()->fetch_assoc();
if(!$asset){http_response_code(404);exit;}
$path=approverAssetPath((int)$account['approver_id'],$asset['filename']);
if(!is_file($path)){http_response_code(404);exit;}
header('Content-Type: image/png');header('X-Content-Type-Options: nosniff');header('Cache-Control: private, no-store');header('Content-Length: '.filesize($path));readfile($path);
