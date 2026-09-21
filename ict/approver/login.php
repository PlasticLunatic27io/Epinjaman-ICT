<?php
require __DIR__ . '/bootstrap.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        approverCsrf();
        $email = strtolower(trim((string)($_POST['email'] ?? '')));
        $password = (string)($_POST['password'] ?? '');
        $conn->begin_transaction();
        $q = $conn->prepare('SELECT *, (locked_until > NOW()) AS is_locked FROM approver_accounts WHERE email = ? FOR UPDATE');
        $q->bind_param('s', $email); $q->execute(); $a = $q->get_result()->fetch_assoc();
        $dummy = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2uheWG/igi';
        $matches = password_verify($password, $a['password_hash'] ?? $dummy);
        $locked = $a && (bool)$a['is_locked'];
        $valid = $a && $a['password_hash'] && !$locked && $matches;
        if ($valid) {
            $q=$conn->prepare('SELECT department_id FROM department_approvers WHERE approval_email = ? AND is_active = 1 LIMIT 1'); $q->bind_param('s',$email);$q->execute();
            $valid=(bool)$q->get_result()->fetch_assoc();
        }
        if (!$valid) {
            if ($a && !$locked) { $q=$conn->prepare('UPDATE approver_accounts SET failed_attempts = failed_attempts + 1, locked_until = IF(failed_attempts >= 5, DATE_ADD(NOW(), INTERVAL 15 MINUTE), NULL) WHERE approver_id = ?');$q->bind_param('i',$a['approver_id']);$q->execute(); }
            $conn->commit();
            throw new InvalidArgumentException('Unable to sign in. Check your details, activate your account, or try again later.');
        }
        $q=$conn->prepare('UPDATE approver_accounts SET failed_attempts = 0, locked_until = NULL WHERE approver_id = ?');$q->bind_param('i',$a['approver_id']);$q->execute();$conn->commit();
        session_regenerate_id(true); $_SESSION['approver_id']=(int)$a['approver_id']; $_SESSION['asset_csrf']=bin2hex(random_bytes(32));
        header('Location: approvals.php');exit;
    } catch (InvalidArgumentException $e) { $error=$e->getMessage(); }
      catch (Throwable $e) { $conn->rollback();error_log('Approver login: '.$e->getMessage());$error='Unable to sign in. Please try again later.'; }
}
approverHeader('Approver Login');
?>
<section class="panel narrow"><p class="eyebrow">DEPARTMENT LEAD ACCESS</p><h1>Welcome back</h1><p class="muted">Sign in with the email registered by JTM.</p>
<?php if ($error): ?><p class="error" role="alert"><?=approverEscape($error)?></p><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?=approverEscape($_SESSION['asset_csrf'])?>"><label for="email">Email address</label><input id="email" name="email" type="email" autocomplete="username" required maxlength="254"><label for="password">Password</label><input id="password" name="password" type="password" autocomplete="current-password" required maxlength="200"><button>Sign In</button></form>
<p class="muted small">First visit? Ask JTM for your account setup link.</p></section>
<?php approverFooter(); ?>
