<?php
require __DIR__.'/bootstrap.php';require_once __DIR__.'/../approval_workflow.php';$account=requireApprover($conn);
$tab=(string)($_GET['tab']??'pending');if(!in_array($tab,['pending','approved','rejected','superseded'],true))$tab='pending';
$rows=wfAll($conn,'SELECT r.* FROM borrowing_approvals r JOIN department_approvers d ON d.department_id=r.department_id WHERE r.approver_id=? AND r.state=? AND d.is_active=1 AND d.approval_email=? ORDER BY r.approval_id DESC','iss',[(int)$account['approver_id'],$tab,$account['email']]);
approverHeader('My Approvals');
?>
<div class="heading"><div><p class="eyebrow">ASSIGNED APPROVALS</p><h1>My Approvals</h1><p class="muted">Review the equipment selection confirmed by JTM.</p></div><a class="button secondary" href="signatures.php">My Signature &amp; Chop</a></div>
<nav class="portal-tabs"><?php foreach(['pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected','superseded'=>'Changed by JTM'] as $key=>$title):?><a class="<?=$tab===$key?'selected':''?>" href="?tab=<?=$key?>"><?=$title?></a><?php endforeach;?></nav>
<section class="panel"><table><thead><tr><th>Request</th><th>Applicant</th><th>Borrowing period</th><th>JTM confirmed</th><th>Action</th></tr></thead><tbody>
<?php foreach($rows as $r):$s=json_decode($r['snapshot_json'],true);?><tr><td>#<?=(int)$r['borrow_id']?> / round <?=(int)$r['round_no']?></td><td><?=approverEscape($s['nama'])?><br><span class="muted"><?=approverEscape($s['unit_bahagian'])?></span></td><td><?=approverEscape($s['start_date'])?><br><?=approverEscape($s['end_date'])?></td><td><?=approverEscape($r['confirmed_name'])?><br><?=approverEscape($r['confirmed_at'])?></td><td><a href="review.php?id=<?=(int)$r['approval_id']?>"><?=$tab==='pending'?'Review':'View'?></a></td></tr><?php endforeach;?>
<?php if(!$rows):?><tr><td colspan="5" class="muted">No requests in this category.</td></tr><?php endif;?></tbody></table></section>
<?php approverFooter(); ?>
