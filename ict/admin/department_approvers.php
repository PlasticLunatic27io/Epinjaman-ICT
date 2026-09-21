<?php
session_start();
require_once __DIR__ . '/admin_auth_guard.php';
$activeAdminAccount = adminRequireActiveAccount($conn ?? null);
if (($_SESSION['role'] ?? '') !== 'admin' || empty($_SESSION['id'])) {
    header('Location: ../login_form.php');
    exit;
}
require_once __DIR__ . '/../dbcon.php';
$conn = dbcon();
$adminId = (int) $_SESSION['id'];
$adminCheck = $conn->prepare('SELECT user_id FROM user WHERE user_id = ?');
$adminCheck->bind_param('i', $adminId);
$adminCheck->execute();
if (!$adminCheck->get_result()->fetch_assoc()) {
    http_response_code(403);
    exit('Access denied.');
}
$_SESSION['department_csrf'] ??= bin2hex(random_bytes(32));
function escapeApprover($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
$departments = [
    'PEJABAT PENGARAH', 'BAHAGIAN OPERASI', 'SEKSYEN PENTADBIRAN',
    'PUSAT SUMBER', 'SEKSYEN SUMBER MANUSIA',
    'SEKSYEN TEKNOLOGI MAKLUMAT & KOMUNIKASI', 'BAHAGIAN AKADEMIK',
    'JABATAN SAINS KOMPUTER & MATEMATIK', 'JABATAN PENGAJIAN UMUM',
    'BAHAGIAN KEWANGAN', 'SEKSYEN REKOD PELAJAR',
    'SEKSYEN PENGAMBILAN PELAJAR', 'JABATAN GRAFIK & MULTIMEDIA',
    'BAHAGIAN HAL EHWAL PELAJAR',
    'PENGARAH', 'TIMBALAN PENGARAH OPERASI', 'TIMBALAN PENGARAH AKADEMIK',
    'TIMBALAN PENGARAH HAL EHWAL PELAJAR', 'TIMBALAN PENGARAH KEWANGAN'
];
$error = '';
$notice = $_SESSION['department_notice'] ?? '';
unset($_SESSION['department_notice']);
$ready = false;
$rows = [];
$setup = $_SESSION['approver_setup'] ?? null;
unset($_SESSION['approver_setup']);
header('Cache-Control: no-store');
header('Referrer-Policy: no-referrer');
try {
    $check = $conn->query("SHOW TABLES LIKE 'department_approvers'");
    $auditCheck = $conn->query("SHOW TABLES LIKE 'department_approver_audit'");
    $ready = $check->num_rows > 0 && $auditCheck->num_rows > 0;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!hash_equals($_SESSION['department_csrf'], (string) ($_POST['csrf'] ?? ''))) {
            throw new InvalidArgumentException('Your session expired. Refresh the page and try again.');
        }
        if (($_POST['action'] ?? '') === 'initialize') {
            $conn->query("CREATE TABLE IF NOT EXISTS department_approvers (
                department_id INT UNSIGNED NOT NULL PRIMARY KEY,
                department_name VARCHAR(160) NOT NULL UNIQUE,
                lead_name VARCHAR(160) NOT NULL DEFAULT '',
                approval_email VARCHAR(254) NOT NULL DEFAULT '',
                account_kind VARCHAR(16) NOT NULL DEFAULT '',
                account_id INT NULL,
                is_active TINYINT(1) NOT NULL DEFAULT 0,
                version INT UNSIGNED NOT NULL DEFAULT 1,
                updated_by INT NULL,
                updated_at DATETIME NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $conn->query("CREATE TABLE IF NOT EXISTS department_approver_audit (
                audit_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                department_id INT UNSIGNED NOT NULL,
                changed_by INT NOT NULL,
                changed_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                old_values TEXT NOT NULL,
                new_values TEXT NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            $seed = $conn->prepare('INSERT IGNORE INTO department_approvers (department_id, department_name) VALUES (?, ?)');
            foreach ($departments as $index => $department) {
                $id = $index + 1;
                $seed->bind_param('is', $id, $department);
                $seed->execute();
            }
            $_SESSION['department_notice'] = 'The department and director approver entries are ready. Enter the lead name and email for each department, then activate the entry.';
            header('Location: department_approvers.php');
            exit;
        }
        if (!$ready) {
            throw new InvalidArgumentException('Initialize the directory first.');
        }
        if (($_POST['action'] ?? '') !== 'save') {
            throw new InvalidArgumentException('Unknown action.');
        }
        $id = filter_var($_POST['department_id'] ?? '', FILTER_VALIDATE_INT);
        $version = filter_var($_POST['version'] ?? '', FILTER_VALIDATE_INT);
        $lead = trim((string) ($_POST['lead_name'] ?? ''));
        $email = strtolower(trim((string) ($_POST['approval_email'] ?? '')));
        $active = isset($_POST['is_active']) ? 1 : 0;
        $kind = '';
        $accountId = null;
        if ($id === false || $version === false || $id < 1 || $id > 19 || mb_strlen($lead) > 160 || strlen($email) > 254) {
            throw new InvalidArgumentException('Please check the department and field lengths.');
        }
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Enter a valid email address.');
        }
        if ($active && ($lead === '' || $email === '')) {
            throw new InvalidArgumentException('An active entry needs a lead name and a valid approval email.');
        }
        $conn->begin_transaction();
        try {
            $read = $conn->prepare('SELECT * FROM department_approvers WHERE department_id = ? FOR UPDATE');
            $read->bind_param('i', $id);
            $read->execute();
            $before = $read->get_result()->fetch_assoc();
            if (!$before || (int) $before['version'] !== $version) {
                throw new InvalidArgumentException('This entry was changed elsewhere. Refresh before saving again.');
            }
            $save = $conn->prepare('UPDATE department_approvers SET lead_name = ?, approval_email = ?, account_kind = ?, account_id = ?, is_active = ?, version = version + 1, updated_by = ?, updated_at = NOW() WHERE department_id = ?');
            $save->bind_param('sssiiii', $lead, $email, $kind, $accountId, $active, $adminId, $id);
            $save->execute();
            $old = json_encode($before, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            $new = json_encode(['lead_name' => $lead, 'approval_email' => $email, 'account_kind' => $kind, 'account_id' => $accountId, 'is_active' => $active], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
            $audit = $conn->prepare('INSERT INTO department_approver_audit (department_id, changed_by, old_values, new_values) VALUES (?, ?, ?, ?)');
            $audit->bind_param('iiss', $id, $adminId, $old, $new);
            $audit->execute();
            $conn->commit();
        } catch (Throwable $failure) {
            $conn->rollback();
            throw $failure;
        }
        $_SESSION['department_notice'] = 'Department approver saved. No email has been sent.';
        header('Location: department_approvers.php');
        exit;
    }
} catch (InvalidArgumentException $failure) {
    $error = $failure->getMessage();
} catch (Throwable $failure) {
    error_log('Department approver directory: ' . $failure->getMessage());
    $error = 'Unable to complete the request. Please try again or contact the system administrator.';
}
try {
    if ($ready) {
        $rows = $conn->query('SELECT * FROM department_approvers ORDER BY department_id')->fetch_all(MYSQLI_ASSOC);
    }
} catch (Throwable $failure) {
    error_log('Department approver list: ' . $failure->getMessage());
    $error = 'Unable to load the directory. Please try again later.';
}
$current_page = 'department_approvers';
$activeCount = count(array_filter($rows, fn($row) => (bool) $row['is_active']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Department Approvers | ePinjamICT</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body{margin:0;background:#fdfcf7;color:#25314b;font-family:'Plus Jakarta Sans',sans-serif}.glass-panel{background:#fffdf8;border-right:1px solid #e7eaf0}.sidebar-item{border-radius:12px;font-size:13px}.sidebar-active{background:white;color:#1e3a8a;box-shadow:0 4px 20px #18336c08}.layout{display:flex;min-height:100vh}.layout>aside{position:sticky;top:0;flex-shrink:0}.content{flex:1;min-width:0;padding:38px 34px}.panel{background:white;border:1px solid #edf0f4;border-radius:22px;padding:24px;margin-bottom:20px}.dept-title{font-size:13px;font-weight:800;color:#1e3a8a}.fields{display:grid;grid-template-columns:1fr 1.25fr;gap:16px;margin-top:20px}label{display:block;font-size:11px;font-weight:700;color:#66738b;margin-bottom:7px}input[type=text],input[type=email],select{width:100%;border:1px solid #dce3ef;border-radius:10px;padding:11px;background:#fafbfe;color:#25314b;font-size:12px}input:focus,select:focus{outline:2px solid #a6bcf4;outline-offset:2px}.button{background:#1e3a8a;color:white;border-radius:10px;padding:10px 17px;font-size:12px;font-weight:700}.badge{font-size:10px;font-weight:800;padding:6px 10px;border-radius:20px;background:#f1f4fa;color:#657086}.badge.active{background:#e7faf1;color:#008354}.feedback{padding:15px;border-radius:12px;margin-bottom:20px;font-size:13px}.bottom{display:flex;justify-content:space-between;align-items:center;margin-top:18px}.toggle{display:flex;align-items:center;gap:8px;font-size:12px;color:#4c5b74}.subtitle{color:#7a879a;font-size:13px;margin-top:9px;line-height:1.7}@media(max-width:1100px){.fields{grid-template-columns:1fr}.content{padding:24px 18px}}@media(max-width:720px){.layout{display:block}.layout>aside{position:relative;width:100%;height:auto}.layout>aside>div.flex-1{max-height:180px}.content{padding:20px 12px}}
</style>
</head>
<body><div class="layout">
<?php require __DIR__ . '/sidebar_component.php'; ?>
<main class="content">
<div class="flex flex-wrap items-start justify-between gap-4 mb-7"><div>
<p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3">Administration / Approval setup</p>
<h1 class="text-3xl font-extrabold text-blue-900">Department Approvers</h1>
<p class="subtitle">Configure department leads and directors available in the Assign to dropdown.</p>
</div><span class="badge"><?= $activeCount ?> / <?= count($rows) ?> ACTIVE</span></div>
<?php if ($notice): ?><div role="status" class="feedback bg-emerald-50 text-emerald-800"><?= escapeApprover($notice) ?></div><?php endif; ?>
<?php if ($error): ?><div role="alert" class="feedback bg-red-50 text-red-800"><?= escapeApprover($error) ?></div><?php endif; ?>
<?php if ($setup): ?>
<div class="panel"><h2 class="font-bold text-sm text-blue-900"><?= $setup['is_new'] ? 'Approver setup link ready' : 'Approver account already activated' ?></h2><p class="subtitle">For <?= escapeApprover($setup['email']) ?>. <?= $setup['is_new'] ? 'Single use; expires in 24 hours. Creating a replacement invalidates the previous setup link.' : 'The lead can sign in with their existing approver password.' ?> No email was sent.</p><a id="setup-link" class="button" style="display:inline-block;margin-top:12px" href="<?= escapeApprover($setup['path']) ?>" target="_blank" rel="noopener">Open <?= $setup['is_new'] ? 'Account Setup' : 'Approver Login' ?></a><button id="copy-link" class="button" style="background:#edf2fb;color:#1e3a8a;margin-left:8px" type="button">Copy Link</button><p class="subtitle">A localhost link works on this laptop. Use your reachable website address when sharing with a lead.</p></div>
<?php endif; ?>
<div class="panel"><h2 class="font-bold text-sm text-blue-900">Approver selection after JTM item confirmation</h2><p class="subtitle">The applicant’s department suggests its lead. JTM chooses one approver from the department leads and directors before submitting. Save a draft while gathering details, then activate it when the name and email are confirmed. Use Create Setup Link to let the lead set a password and upload their signature and chop. This directory does not send notifications or grant admin access.</p></div>
<?php if (!$ready): ?>
<div class="panel"><h2 class="font-bold text-blue-900">Set up department leads and directors</h2><p class="subtitle mb-5">Create the approval directory and its change history. Existing applicants, inventory and borrowing records remain intact.</p>
<form method="post"><input type="hidden" name="csrf" value="<?= escapeApprover($_SESSION['department_csrf']) ?>"><input type="hidden" name="action" value="initialize"><button class="button" type="submit">Initialize Department Directory</button></form></div>
<?php else: ?>
<div class="mb-5"><label for="search">Find a department or lead</label><input id="search" type="text" placeholder="Search departments, lead names or emails..." style="max-width:440px"></div>
<p id="no-results" hidden class="subtitle">No matching departments.</p>
<?php foreach ($rows as $row): ?>
<section class="panel department" data-search="<?= escapeApprover(strtolower($row['department_name'].' '.$row['lead_name'].' '.$row['approval_email'])) ?>">
<form method="post">
<input type="hidden" name="csrf" value="<?= escapeApprover($_SESSION['department_csrf']) ?>"><input type="hidden" name="action" value="save"><input type="hidden" name="department_id" value="<?= (int)$row['department_id'] ?>"><input type="hidden" name="version" value="<?= (int)$row['version'] ?>">
<div class="flex flex-wrap justify-between items-center gap-3"><h2 class="dept-title"><?= escapeApprover($row['department_name']) ?></h2><span class="badge <?= $row['is_active'] ? 'active' : '' ?>"><?= $row['is_active'] ? 'ACTIVE' : ($row['lead_name'] ? 'DRAFT' : 'NOT CONFIGURED') ?></span></div>
<div class="fields">
<div><label for="lead-<?= $row['department_id'] ?>">Approver’s name</label><input id="lead-<?= $row['department_id'] ?>" name="lead_name" type="text" maxlength="160" value="<?= escapeApprover($row['lead_name']) ?>" placeholder="Full name"></div>
<div><label for="email-<?= $row['department_id'] ?>">Approval email</label><input id="email-<?= $row['department_id'] ?>" name="approval_email" type="email" maxlength="254" value="<?= escapeApprover($row['approval_email']) ?>" placeholder="lead@company.com"></div>
</div><div class="bottom"><label class="toggle"><input type="checkbox" name="is_active" value="1" <?= $row['is_active'] ? 'checked' : '' ?>> Active approval routing</label><button type="submit" class="button">Save Approver</button></div>
<?php if ($row['updated_at']): ?><p class="text-xs text-slate-400 mt-3">Last updated: <?= escapeApprover($row['updated_at']) ?></p><?php endif; ?>
</form>
<?php if ($row['is_active'] && $row['lead_name'] && $row['approval_email']): ?>
<form method="post" action="approver_setup.php" style="margin-top:12px"><input type="hidden" name="csrf" value="<?= escapeApprover($_SESSION['department_csrf']) ?>"><input type="hidden" name="department_id" value="<?= (int)$row['department_id'] ?>"><button class="button" style="background:#edf2fb;color:#1e3a8a" type="submit">Create Setup Link</button><span class="subtitle" style="margin-left:10px">Save changes above before creating a link.</span></form>
<?php endif; ?>
</section>
<?php endforeach; ?>
<?php endif; ?>
</main></div>
<script>
document.getElementById('copy-link')?.addEventListener('click', async () => { const url = document.getElementById('setup-link').href; try { await navigator.clipboard.writeText(url); document.getElementById('copy-link').textContent = 'Copied'; } catch { window.prompt('Copy this setup link:', url); } });
const search = document.getElementById('search');
if (search) search.addEventListener('input', () => {
    let visible = 0;
    const term = search.value.trim().toLowerCase();
    document.querySelectorAll('.department').forEach(card => {
        card.hidden = !card.dataset.search.includes(term);
        if (!card.hidden) visible++;
    });
    document.getElementById('no-results').hidden = visible !== 0;
});
</script></body></html>
