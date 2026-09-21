<?php
require __DIR__ . '/bootstrap.php';
$error='';$done=false;
$token=(string)($_POST['token'] ?? $_GET['token'] ?? '');
$hash=hash('sha256',$token);
$q=$conn->prepare('SELECT approver_id, display_name, email FROM approver_accounts WHERE activation_hash = ? AND activation_expires > NOW() AND password_hash IS NULL');
$q->bind_param('s',$hash);$q->execute();$a=$q->get_result()->fetch_assoc();
if (!$a || !preg_match('/^[a-f0-9]{64}$/D',$token)) $error='This setup link is invalid or expired. Ask JTM for a new link.';
elseif ($_SERVER['REQUEST_METHOD']==='POST') {
    try {
        approverCsrf();$password=(string)($_POST['password']??'');
        if (strlen($password)<12 || strlen($password)>72 || $password !== (string)($_POST['confirm']??'')) throw new InvalidArgumentException('Use 12–72 characters and enter the same password twice.');
        $passwordHash=password_hash($password,PASSWORD_DEFAULT);
        $q=$conn->prepare('UPDATE approver_accounts SET password_hash = ?, activation_hash = NULL, activation_expires = NULL WHERE activation_hash = ? AND activation_expires > NOW() AND password_hash IS NULL');
        $q->bind_param('ss',$passwordHash,$hash);$q->execute();
        if ($q->affected_rows!==1) throw new InvalidArgumentException('This link has already been used or expired.');
        $done=true;
    } catch(InvalidArgumentException $e){$error=$e->getMessage();}
      catch(Throwable $e){error_log('Approver activation: '.$e->getMessage());$error='Unable to activate your account. Please try again later.';}
}
approverHeader('Set Up Your Account');
?>
<section class="panel narrow"><p class="eyebrow">FIRST-TIME SETUP</p><h1>Your approver account</h1>
<?php if($error):?><p role="alert" class="error"><?=approverEscape($error)?></p><?php endif;?>
<?php if($done):?><p class="success">Your password is set. Sign in to upload your signature and chop.</p><a class="button" href="login.php">Continue to Sign In</a>
<?php elseif($a):?><p class="muted"><?=approverEscape($a['display_name'])?> · <?=approverEscape($a['email'])?></p><form method="post" action="activate.php"><input type="hidden" name="csrf" value="<?=approverEscape($_SESSION['asset_csrf'])?>"><input type="hidden" name="token" value="<?=approverEscape($token)?>"><label for="password">Create password</label><input id="password" name="password" type="password" minlength="12" maxlength="72" autocomplete="new-password" required><label for="confirm">Confirm password</label><input id="confirm" name="confirm" type="password" minlength="12" maxlength="72" autocomplete="new-password" required><button>Activate Account</button></form><?php endif;?></section>
<?php approverFooter(); ?>
