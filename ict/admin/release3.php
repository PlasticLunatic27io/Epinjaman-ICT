<?php
$id=filter_var($_GET['id']??'',FILTER_VALIDATE_INT);
if(!$id){http_response_code(404);exit('Request not found.');}
header('Location: release.php?id='.$id, true, 303);
exit;
