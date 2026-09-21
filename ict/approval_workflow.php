<?php
require_once __DIR__.'/approver/core.php';
function wfQuery($conn,$sql,$types='',$values=[]){$q=$conn->prepare($sql);if($types!=='')$q->bind_param($types,...$values);$q->execute();return $q;}
function wfRow($conn,$sql,$types='',$values=[]){return wfQuery($conn,$sql,$types,$values)->get_result()->fetch_assoc();}
function wfAll($conn,$sql,$types='',$values=[]){return wfQuery($conn,$sql,$types,$values)->get_result()->fetch_all(MYSQLI_ASSOC);}
function wfExec($conn,$sql,$types='',$values=[]){$q=wfQuery($conn,$sql,$types,$values);return $q->affected_rows;}
function wfDepartmentKey($value){return preg_replace('/[^A-Z0-9]/','',strtoupper($value));}
function wfDepartment($conn,$name){
 $key=wfDepartmentKey($name);
 $aliases=['FSKTM'=>'JABATAN SAINS KOMPUTER & MATEMATIK','JABATAN SAINS KOMPUTER MATEMATIK'=>'JABATAN SAINS KOMPUTER & MATEMATIK','JTM'=>'SEKSYEN TEKNOLOGI MAKLUMAT & KOMUNIKASI','ICT'=>'SEKSYEN TEKNOLOGI MAKLUMAT & KOMUNIKASI','UNIT ICT'=>'SEKSYEN TEKNOLOGI MAKLUMAT & KOMUNIKASI','JABATAN TEKNOLOGI MAKLUMAT'=>'SEKSYEN TEKNOLOGI MAKLUMAT & KOMUNIKASI'];
 foreach($aliases as $alias=>$target)if($key===wfDepartmentKey($alias))$key=wfDepartmentKey($target);
 foreach(wfAll($conn,'SELECT * FROM department_approvers') as $d)if(wfDepartmentKey($d['department_name'])===$key)return $d;
 return null;
}
function wfApproverPosition($d){
 $roles=[15=>'Director',16=>'Deputy Director of Operations',17=>'Deputy Director of Academic',18=>'Deputy Director of Student Affairs',19=>'Deputy Director of Finance'];
 return $roles[(int)$d['department_id']]??'Department / Section Lead';
}
function wfApproverChoices($conn){return wfAll($conn,"SELECT d.*,a.approver_id,IF(a.password_hash IS NOT NULL,1,0) AS account_ready,EXISTS(SELECT 1 FROM approver_assets x WHERE x.approver_id=a.approver_id AND x.asset_kind='combined') AS image_ready FROM department_approvers d LEFT JOIN approver_accounts a ON a.email=d.approval_email ORDER BY d.department_id");}
function wfSnapshot($conn,$borrowId){
 $b=wfRow($conn,'SELECT * FROM borrowing WHERE borrow_id = ?','i',[$borrowId]);if(!$b)throw new InvalidArgumentException('Request not found.');
 $p=wfRow($conn,'SELECT * FROM pemohon WHERE pemohon_college_id = ? ORDER BY pemohon_id LIMIT 1','s',[$b['pemohon_college_id']]);if(!$p)throw new InvalidArgumentException('Applicant record not found.');
 $items=wfAll($conn,'SELECT bi.item_tag,bi.item_qty,bi.item_siri,i.item_name,i.item_description FROM borrowing_item bi LEFT JOIN item i ON i.item_tag = bi.item_tag WHERE bi.borrow_id = ? ORDER BY bi.item_tag,bi.borrow_item_id','s',[(string)$borrowId]);
 return ['borrow_id'=>$borrowId,'applicant_id'=>(int)$p['pemohon_id'],'nama'=>$b['applicant_name']?:$p['pemohon_name'],'no_id_staf'=>$p['pemohon_college_id'],'no_tel'=>$b['applicant_phone']?:$p['pemohon_no_tel'],'jawatan'=>$p['pemohon_position'],'unit_bahagian'=>$b['applicant_department']?:$p['pemohon_dept'],'applicant_email'=>$p['pemohon_email']??'','requested_items'=>$b['borrow_item'],'tujuan_tempahan'=>$b['purpose'],'start_date'=>$b['start_date'],'end_date'=>$b['end_date'],'lokasi_penggunaan'=>$b['location'],'type'=>$b['type'],'equipment'=>array_map(fn($i)=>['name'=>$i['item_name']??'','serial_no'=>$i['item_siri']??'','tagging_no'=>$i['item_tag'],'quantity'=>(int)$i['item_qty'],'description'=>$i['item_description']??''],$items)];
}
function wfJson($snapshot){return json_encode($snapshot,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_THROW_ON_ERROR);}
function wfDigest($snapshot){return hash('sha256',wfJson($snapshot));}
function wfLatest($conn,$borrowId){return wfRow($conn,'SELECT * FROM borrowing_approvals WHERE borrow_id = ? ORDER BY approval_id DESC LIMIT 1','i',[$borrowId]);}
function wfEvent($conn,$approvalId,$role,$actorId,$action,$detail=''){wfExec($conn,'INSERT INTO borrowing_approval_events (approval_id,actor_role,actor_id,action,detail) VALUES (?,?,?,?,?)','isiss',[$approvalId,$role,$actorId,$action,$detail]);}
function wfResetApproval($conn,$borrowId,$adminId){
 $last=wfLatest($conn,$borrowId);
 if($last && $last['handed_over_at'])throw new InvalidArgumentException('Equipment was already handed over. Use the return process instead.');
 if($last && in_array($last['state'],['pending','approved'],true)){wfExec($conn,"UPDATE borrowing_approvals SET state = 'superseded' WHERE approval_id = ?",'i',[$last['approval_id']]);wfEvent($conn,(int)$last['approval_id'],'admin',$adminId,'items_changed','Approval invalidated; fresh confirmation required.');}
 wfExec($conn,"UPDATE borrowing SET status = 'Baru', approved_date = '1970-01-01 00:00:00' WHERE borrow_id = ?",'i',[$borrowId]);
}
function wfReleaseReservations($conn,$borrowId){
 foreach(wfAll($conn,'SELECT item_tag,status FROM borrowing_item WHERE borrow_id = ?','s',[(string)$borrowId]) as $i){
  if($i['status']==='Borrowed')throw new InvalidArgumentException('Borrowed equipment must be handled through the return process.');
  wfExec($conn,"UPDATE item SET item_status = 'Available' WHERE item_tag = ? AND item_status = 'Reserved'",'s',[$i['item_tag']]);
 }
 wfExec($conn,'DELETE FROM borrowing_item WHERE borrow_id = ?','s',[(string)$borrowId]);
}
function wfAdminAction($conn,$borrowId,$adminId,$action,$input){
 $conn->begin_transaction();
 try{
  $b=wfRow($conn,'SELECT * FROM borrowing WHERE borrow_id = ? FOR UPDATE','i',[$borrowId]);if(!$b)throw new InvalidArgumentException('Request not found.');
  $last=wfLatest($conn,$borrowId);$admin=wfRow($conn,'SELECT name FROM user WHERE user_id = ?','i',[$adminId]);if(!$admin)throw new InvalidArgumentException('JTM account not found.');
  if(!$last && $b['status']!=='Baru')throw new InvalidArgumentException('This is a historical request. Its existing records are unchanged.');
  if($last && $last['handed_over_at'])throw new InvalidArgumentException('This request was already handed over.');
  $note=trim((string)($input['note']??''));if(mb_strlen($note)>100)throw new InvalidArgumentException('Keep remarks within 100 characters.');
  if($action==='add'){
   $tag=trim((string)($input['tagno']??''));if($tag===''||strlen($tag)>50)throw new InvalidArgumentException('Select an available asset.');
   $item=wfRow($conn,'SELECT * FROM item WHERE item_tag = ? FOR UPDATE','s',[$tag]);if(!$item||$item['item_status']!=='Available')throw new InvalidArgumentException('That asset is no longer available.');
   if(wfRow($conn,"SELECT borrow_item_id FROM borrowing_item WHERE item_tag = ? AND status IN ('Reserved','Borrowed') LIMIT 1",'s',[$tag]))throw new InvalidArgumentException('That asset is already allocated to another request.');
   wfResetApproval($conn,$borrowId,$adminId);
   wfExec($conn,"INSERT INTO borrowing_item (borrow_id,pemohon_college_id,item_tag,item_qty,item_siri,status,return_date,notes) VALUES (?,?,?,?,?,'Reserved','1970-01-01 00:00:00','')",'sssis',[(string)$borrowId,$b['pemohon_college_id'],$tag,(int)$item['item_quantity'],$item['item_siri']]);
   wfExec($conn,"UPDATE item SET item_status = 'Reserved' WHERE item_id = ?",'i',[$item['item_id']]);$message='Asset reserved. Confirm the final selection to request approval.';
  }elseif($action==='remove'){
   $itemId=(int)($input['item_id']??0);$i=wfRow($conn,'SELECT * FROM borrowing_item WHERE borrow_item_id = ? AND borrow_id = ? FOR UPDATE','is',[$itemId,(string)$borrowId]);if(!$i)throw new InvalidArgumentException('Selected asset not found.');
   if($i['status']==='Borrowed')throw new InvalidArgumentException('Borrowed equipment must use the return process.');
   wfResetApproval($conn,$borrowId,$adminId);
   wfExec($conn,'DELETE FROM borrowing_item WHERE borrow_item_id = ?','i',[$itemId]);wfExec($conn,"UPDATE item SET item_status = 'Available' WHERE item_tag = ? AND item_status = 'Reserved'",'s',[$i['item_tag']]);$message='Asset removed. Any previous approval is invalidated.';
  }elseif($action==='submit'){
   if($last && in_array($last['state'],['pending','approved'],true))throw new InvalidArgumentException('This selection is already awaiting approval or approved.');
   $s=wfSnapshot($conn,$borrowId);if(!$s['equipment'])throw new InvalidArgumentException('Select at least one inventory item first.');
   foreach(wfAll($conn,'SELECT bi.status,i.item_status FROM borrowing_item bi JOIN item i ON i.item_tag = bi.item_tag WHERE bi.borrow_id = ?','s',[(string)$borrowId]) as $i)if($i['status']!=='Reserved'||$i['item_status']!=='Reserved')throw new InvalidArgumentException('The selected equipment is not reserved correctly. Remove and select it again.');
   $suggested=wfDepartment($conn,$s['unit_bahagian']);
   $selected=filter_var($input['approver_department_id']??($suggested['department_id']??0),FILTER_VALIDATE_INT);
   if(!$selected)throw new InvalidArgumentException('Select an approver.');
   $d=wfRow($conn,'SELECT * FROM department_approvers WHERE department_id=? FOR UPDATE','i',[$selected]);
   if(!$d||!$d['is_active']||trim($d['lead_name'])==='')throw new InvalidArgumentException('Select an active configured approver.');
   $reason=trim((string)($input['assignment_reason']??''));
   if(mb_strlen($reason)>300)throw new InvalidArgumentException('Keep the assignment reason within 300 characters.');
   $override=!$suggested||(int)$suggested['department_id']!==(int)$selected;
   if($override&&$reason==='')throw new InvalidArgumentException('Enter a reason for choosing a different approver.');
   $a=wfRow($conn,'SELECT approver_id,email FROM approver_accounts WHERE email = ? AND password_hash IS NOT NULL','s',[$d['approval_email']]);if(!$a)throw new InvalidArgumentException('The selected approver must activate their account first.');
   if(strcasecmp(trim($s['applicant_email']),$a['email'])===0)throw new InvalidArgumentException('Applicants cannot approve their own request. Select another authorized approver.');
   $json=wfJson($s);$digest=wfDigest($s);$round=$last?(int)$last['round_no']+1:1;
   wfExec($conn,"INSERT INTO borrowing_approvals (borrow_id,round_no,department_id,approver_id,lead_name,lead_email,confirmed_by,confirmed_name,jtm_note,snapshot_json,snapshot_hash,state) VALUES (?,?,?,?,?,?,?,?,?,?,?,'pending')",'iiiississss',[$borrowId,$round,$d['department_id'],$a['approver_id'],$d['lead_name'],$a['email'],$adminId,$admin['name'],$note,$json,$digest]);
   $approvalId=$conn->insert_id;wfEvent($conn,$approvalId,'admin',$adminId,'submitted',$note);
   wfEvent($conn,$approvalId,'admin',$adminId,'approver_assigned',wfJson(['applicant_department'=>$s['unit_bahagian'],'suggested_lead'=>$suggested['lead_name']??null,'selected_name'=>$d['lead_name'],'selected_position'=>wfApproverPosition($d),'selected_department'=>$d['department_name'],'selected_email'=>$a['email'],'override'=>$override,'reason'=>$reason]));
   wfExec($conn,"UPDATE borrowing SET status = 'Menunggu Kelulusan', user_name = ?, notes = ? WHERE borrow_id = ?",'ssi',[$admin['name'],$note,$borrowId]);$message='Selection confirmed. The request is waiting for approval — '.$d['lead_name'].'. No email was sent.';
  }elseif($action==='reject'){
   if($note==='')throw new InvalidArgumentException('Enter a reason for rejection.');
   if($last && $last['state']==='approved')throw new InvalidArgumentException('Change the selection to invalidate approval before cancelling this request.');
   wfReleaseReservations($conn,$borrowId);
   if($last && $last['state']==='pending'){wfExec($conn,"UPDATE borrowing_approvals SET state = 'rejected', decision_at = NOW(), decision_note = ? WHERE approval_id = ?",'si',[$note,$last['approval_id']]);wfEvent($conn,(int)$last['approval_id'],'admin',$adminId,'rejected',$note);}
   wfExec($conn,"UPDATE borrowing SET status = 'Ditolak', user_name = ?, notes = ? WHERE borrow_id = ?",'ssi',[$admin['name'],$note,$borrowId]);$message='Request rejected; reserved equipment is available again.';
  }elseif($action==='handover'){
   if(!$last||$last['state']!=='approved'||!$last['pdf_filename'])throw new InvalidArgumentException('Department approval and signed PDF are required before handover.');
   if(!is_file(wfPdfPath($last['pdf_filename'])))throw new InvalidArgumentException('Signed PDF is unavailable. Contact JTM before handover.');
   if(!hash_equals($last['snapshot_hash'],wfDigest(wfSnapshot($conn,$borrowId))))throw new InvalidArgumentException('Request details changed. Change the selection and obtain fresh approval.');
   $items=wfAll($conn,'SELECT bi.borrow_item_id,bi.item_tag,bi.status,i.item_status FROM borrowing_item bi JOIN item i ON i.item_tag=bi.item_tag WHERE bi.borrow_id = ? FOR UPDATE','s',[(string)$borrowId]);if(!$items)throw new InvalidArgumentException('No equipment selected.');
   foreach($items as $i){if($i['status']!=='Reserved'||$i['item_status']!=='Reserved')throw new InvalidArgumentException('Equipment reservation changed. Verify the selection.');wfExec($conn,"UPDATE item SET item_status='Borrowed' WHERE item_tag = ?",'s',[$i['item_tag']]);}
   wfExec($conn,"UPDATE borrowing_item SET status='Borrowed' WHERE borrow_id = ?",'s',[(string)$borrowId]);wfExec($conn,'UPDATE borrowing_approvals SET handed_over_at=NOW(),handed_over_by=? WHERE approval_id = ?','ii',[$adminId,$last['approval_id']]);wfEvent($conn,(int)$last['approval_id'],'admin',$adminId,'handed_over',$note);$message='Handover confirmed. Equipment is now recorded as borrowed.';
  }else throw new InvalidArgumentException('Unknown action.');
  $conn->commit();return $message;
 }catch(Throwable $e){$conn->rollback();throw $e;}
}
function wfPdfPath($filename){if(!preg_match('/^approval_[0-9]+_[a-f0-9]{32}\.pdf$/D',$filename))throw new RuntimeException('Invalid document reference.');return approverStore().'/approved-documents/'.$filename;}
function wfGeneratePdf($record,$snapshot,$asset,$decisionAt){
 $image=approverAssetPath((int)$record['approver_id'],$asset['filename']);if(!is_file($image))throw new InvalidArgumentException('Your saved signature-and-chop image is missing. Upload it again.');
 if(!hash_equals($asset['sha256'],hash_file('sha256',$image)))throw new InvalidArgumentException('Your saved image changed unexpectedly. Upload it again.');
 $snapshot['tarikh_dari']=substr($snapshot['start_date'],0,10);$snapshot['masa_dari']=substr($snapshot['start_date'],11);$snapshot['tarikh_hingga']=substr($snapshot['end_date'],0,10);$snapshot['masa_hingga']=substr($snapshot['end_date'],11);
 $snapshot['approval']=['id'=>(int)$record['approval_id'],'round'=>(int)$record['round_no'],'lead_name'=>$record['lead_name'],'image_path'=>$image,'approved_at'=>$decisionAt,'jtm_name'=>$record['confirmed_name'],'confirmed_at'=>$record['confirmed_at'],'snapshot_hash'=>$record['snapshot_hash']];
 $filename='approval_'.$record['approval_id'].'_'.bin2hex(random_bytes(16)).'.pdf';$output=wfPdfPath($filename);$dir=dirname($output);if(!is_dir($dir)&&!mkdir($dir,0700,true))throw new RuntimeException('Cannot create document directory.');
 $json=tempnam(sys_get_temp_dir(),'ict_approval_');if($json===false)throw new RuntimeException('Cannot create temporary data.');
 try{
  if(file_put_contents($json,wfJson($snapshot))===false)throw new RuntimeException('Cannot write approval data.');
  $python=getenv('ICT_PYTHON_EXECUTABLE')?:'C:/Users/User/.cache/codex-runtimes/codex-primary-runtime/dependencies/python/python.exe';
  $command=escapeshellarg($python).' -X utf8 '.escapeshellarg(__DIR__.'/admin/fill_jtm_overlay.py').' '.escapeshellarg(__DIR__.'/admin/jtm.pdf').' '.escapeshellarg($json).' '.escapeshellarg($output).' 2>&1';
  $lines=[];$exit=1;exec($command,$lines,$exit);
  if($exit!==0||!is_file($output)||filesize($output)<100||file_get_contents($output,false,null,0,4)!=='%PDF'){error_log('Approval PDF generation: '.implode("\n",$lines));throw new RuntimeException('PDF generation failed.');}
  return $filename;
 }catch(Throwable $e){if(is_file($output))@unlink($output);throw $e;}finally{@unlink($json);}
}
function wfApproverDecision($conn,$approvalId,$approverId,$decision,$note){
 $output=null;$conn->begin_transaction();
 try{
  $ref=wfRow($conn,'SELECT borrow_id FROM borrowing_approvals WHERE approval_id = ?','i',[$approvalId]);if(!$ref)throw new InvalidArgumentException('Request not found.');
  wfRow($conn,'SELECT borrow_id FROM borrowing WHERE borrow_id = ? FOR UPDATE','i',[$ref['borrow_id']]);
  $r=wfRow($conn,'SELECT * FROM borrowing_approvals WHERE approval_id = ? FOR UPDATE','i',[$approvalId]);
  $a=wfRow($conn,'SELECT * FROM approver_accounts WHERE approver_id = ? FOR UPDATE','i',[$approverId]);
  $d=wfRow($conn,'SELECT * FROM department_approvers WHERE department_id = ?','i',[$r['department_id']]);
  if((int)$r['approver_id']!==$approverId||!$d||!$d['is_active']||strcasecmp($d['approval_email'],$a['email'])!==0)throw new InvalidArgumentException('This request is not available for your approval.');
  if($r['state']!=='pending')throw new InvalidArgumentException('This request was already decided or changed by JTM. Refresh the list.');
  if((int)wfLatest($conn,(int)$r['borrow_id'])['approval_id']!==$approvalId)throw new InvalidArgumentException('This approval round is no longer current.');
  $s=wfSnapshot($conn,(int)$r['borrow_id']);if(!hash_equals($r['snapshot_hash'],wfDigest($s)))throw new InvalidArgumentException('The request details changed. Ask JTM to reconfirm and submit it again.');
  if(strcasecmp(trim($s['applicant_email']),$a['email'])===0)throw new InvalidArgumentException('You cannot approve your own request.');
  $note=trim($note);if(mb_strlen($note)>1000)throw new InvalidArgumentException('Keep comments within 1000 characters.');
  if($decision==='approve'){
   $asset=wfRow($conn,"SELECT * FROM approver_assets WHERE approver_id = ? AND asset_kind = 'combined'",'i',[$approverId]);if(!$asset)throw new InvalidArgumentException('Upload your combined signature-and-chop image before approving.');
   foreach(wfAll($conn,'SELECT bi.status,i.item_status FROM borrowing_item bi JOIN item i ON i.item_tag=bi.item_tag WHERE bi.borrow_id = ?', 's',[(string)$r['borrow_id']]) as $selected) if($selected['status']!=='Reserved'||$selected['item_status']!=='Reserved') throw new InvalidArgumentException('Equipment reservations changed. Ask JTM to verify and reconfirm.');
   $when=wfRow($conn,'SELECT NOW() AS value')['value'];$output=wfGeneratePdf($r,$s,$asset,$when);
   wfExec($conn,"UPDATE borrowing_approvals SET state='approved',decision_at=?,decision_note=?,signed_image_hash=?,pdf_filename=? WHERE approval_id=?",'ssssi',[$when,$note,$asset['sha256'],$output,$approvalId]);
   wfExec($conn,"UPDATE borrowing SET status='Lulus',approved_date=?,user_name=? WHERE borrow_id=?",'ssi',[$when,mb_substr($r['lead_name'],0,50),$r['borrow_id']]);wfEvent($conn,$approvalId,'approver',$approverId,'approved',$note);
  }elseif($decision==='reject'){
   if($note==='')throw new InvalidArgumentException('Enter a reason for rejection.');
   wfReleaseReservations($conn,(int)$r['borrow_id']);wfExec($conn,"UPDATE borrowing_approvals SET state='rejected',decision_at=NOW(),decision_note=? WHERE approval_id=?",'si',[$note,$approvalId]);wfExec($conn,"UPDATE borrowing SET status='Ditolak',notes=? WHERE borrow_id=?",'si',[mb_substr($note,0,100),$r['borrow_id']]);wfEvent($conn,$approvalId,'approver',$approverId,'rejected',$note);
  }else throw new InvalidArgumentException('Unknown decision.');
  $conn->commit();return $decision==='approve'?'Approved and signed. JTM can now arrange handover.':'Rejected. Reserved equipment has been released.';
 }catch(Throwable $e){$conn->rollback();if($output && is_file(wfPdfPath($output)))@unlink(wfPdfPath($output));throw $e;}
}
