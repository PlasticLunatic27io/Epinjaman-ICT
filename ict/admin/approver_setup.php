<?php
session_start();
require_once __DIR__ . '/admin_auth_guard.php';
$activeAdminAccount = adminRequireActiveAccount($conn ?? null);
if(($_SESSION['role']??'')!=='admin'||empty($_SESSION['id'])){header('Location: ../login_form.php');exit;}
require_once __DIR__.'/../dbcon.php';$conn=dbcon();
$adminId=(int)$_SESSION['id'];$q=$conn->prepare('SELECT user_id FROM user WHERE user_id = ?');$q->bind_param('i',$adminId);$q->execute();if(!$q->get_result()->fetch_assoc()){http_response_code(403);exit('Access denied.');}
if($_SERVER['REQUEST_METHOD']!=='POST'){http_response_code(405);header('Allow: POST');exit;}
if(!isset($_SESSION['department_csrf'])||!hash_equals($_SESSION['department_csrf'],(string)($_POST['csrf']??''))){http_response_code(403);exit('Invalid request. Refresh the directory and try again.');}
try{
    $id=filter_var($_POST['department_id']??'',FILTER_VALIDATE_INT);
    if(!$id)throw new InvalidArgumentException('Choose a department.');
    $conn->begin_transaction();
    $q=$conn->prepare('SELECT * FROM department_approvers WHERE department_id = ? FOR UPDATE');$q->bind_param('i',$id);$q->execute();$d=$q->get_result()->fetch_assoc();
    if(!$d||!$d['is_active']||!$d['lead_name']||!filter_var($d['approval_email'],FILTER_VALIDATE_EMAIL))throw new InvalidArgumentException('Save and activate the lead name and approval email first.');
    $email=strtolower(trim($d['approval_email']));$name=$d['lead_name'];
    $q=$conn->prepare('INSERT INTO approver_accounts (email,display_name) VALUES (?,?) ON DUPLICATE KEY UPDATE display_name = VALUES(display_name)');$q->bind_param('ss',$email,$name);$q->execute();
    $q=$conn->prepare('SELECT approver_id,password_hash FROM approver_accounts WHERE email = ? FOR UPDATE');$q->bind_param('s',$email);$q->execute();$a=$q->get_result()->fetch_assoc();
    $token=null;
    if(!$a['password_hash']){
        $token=bin2hex(random_bytes(32));$hash=hash('sha256',$token);
        $q=$conn->prepare('UPDATE approver_accounts SET activation_hash = ?, activation_expires = DATE_ADD(NOW(), INTERVAL 24 HOUR) WHERE approver_id = ?');$q->bind_param('si',$hash,$a['approver_id']);$q->execute();
    }
    $event=json_encode(['action'=>$token?'setup_link_created':'existing_account_access','approver_id'=>$a['approver_id'],'email'=>$email]);$empty='{}';
    $q=$conn->prepare('INSERT INTO department_approver_audit (department_id,changed_by,old_values,new_values) VALUES (?,?,?,?)');$q->bind_param('iiss',$id,$adminId,$empty,$event);$q->execute();$conn->commit();
    $_SESSION['approver_setup']=['department_id'=>$id,'email'=>$email,'path'=>$token?'../approver/activate.php?token='.$token:'../approver/login.php','is_new'=>(bool)$token];
    header('Location: department_approvers.php');exit;
}catch(InvalidArgumentException $e){$conn->rollback();$_SESSION['department_notice']=$e->getMessage();header('Location: department_approvers.php');exit;}
 catch(Throwable $e){$conn->rollback();error_log('Approver setup: '.$e->getMessage());http_response_code(500);exit('Unable to create the setup link. Please try again later.');}
