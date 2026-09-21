<?php
require __DIR__ . '/bootstrap.php';
$account=requireApprover($conn);$id=(int)$account['approver_id'];$error='';$notice=$_SESSION['asset_notice']??'';unset($_SESSION['asset_notice']);
if($_SERVER['REQUEST_METHOD']==='POST'){
    $created=[];$pending=[];
    try{
        approverCsrf();
        foreach(['combined'] as $kind){
            $file=$_FILES[$kind]??['error'=>UPLOAD_ERR_NO_FILE];
            if($file['error']===UPLOAD_ERR_NO_FILE)continue;
            $image=approverImage($file);
            $filename=$kind.'_'.bin2hex(random_bytes(16)).'.png';
            $path=approverAssetPath($id,$filename);$dir=dirname($path);
            if(!is_dir($dir) && !mkdir($dir,0700,true))throw new RuntimeException('Unable to create image folder.');
            $created[]=$path;
            if(!imagepng($image,$path)){imagedestroy($image);throw new RuntimeException('Unable to save image.');}
            imagedestroy($image);@chmod($path,0600);
            $pending[]=['kind'=>$kind,'file'=>$filename,'sha256'=>hash_file('sha256',$path)];
        }
        if(!$pending)throw new InvalidArgumentException('Choose an image containing both your signature and chop before saving.');
        $conn->begin_transaction();
        try{
            // Lock the account so concurrent replacements cannot leave duplicate records.
            $q=$conn->prepare('SELECT approver_id FROM approver_accounts WHERE approver_id = ? FOR UPDATE');$q->bind_param('i',$id);$q->execute();$q->get_result()->fetch_assoc();
            $q=$conn->prepare('SELECT filename FROM approver_assets WHERE approver_id = ?');$q->bind_param('i',$id);$q->execute();$previous=$q->get_result()->fetch_all(MYSQLI_ASSOC);
            $q=$conn->prepare('DELETE FROM approver_assets WHERE approver_id = ?');$q->bind_param('i',$id);$q->execute();
            $asset=$pending[0];$version=1;
            $q=$conn->prepare('INSERT INTO approver_assets (approver_id,asset_kind,version,filename,sha256) VALUES (?,?,?,?,?)');$q->bind_param('isiss',$id,$asset['kind'],$version,$asset['file'],$asset['sha256']);$q->execute();
            $conn->commit();
        }catch(Throwable $e){$conn->rollback();throw $e;}
        foreach($previous as $oldImage) removePreviousApproverImage($id,$oldImage['filename']);
        $_SESSION['asset_notice']='Your signature-and-chop image was saved privately. It replaces your previous image. No request has been signed.';
        header('Location: signatures.php');exit;
    }catch(InvalidArgumentException $e){$error=$e->getMessage();foreach($created as $path)@unlink($path);}
     catch(Throwable $e){error_log('Approver image upload: '.$e->getMessage());$error='Unable to save your image. Please try again or contact JTM.';foreach($created as $path)@unlink($path);}
}
$q=$conn->prepare('SELECT * FROM approver_assets WHERE approver_id = ? ORDER BY asset_id DESC');$q->bind_param('i',$id);$q->execute();$all=$q->get_result()->fetch_all(MYSQLI_ASSOC);$latest=[];foreach($all as $asset){$latest[$asset['asset_kind']]??=$asset;}
$q=$conn->prepare('SELECT department_name FROM department_approvers WHERE approval_email = ? AND is_active = 1');$q->bind_param('s',$account['email']);$q->execute();$departments=array_column($q->get_result()->fetch_all(MYSQLI_ASSOC),'department_name');
approverHeader('My Signature & Chop');
?>
<div class="heading"><div><p class="eyebrow">MY SIGNATURE & CHOP</p><h1>Ready for your approval</h1><p class="muted"><?=approverEscape($account['display_name'])?> · <?=approverEscape(implode(', ',$departments))?></p></div><form method="post" action="logout.php"><input type="hidden" name="csrf" value="<?=approverEscape($_SESSION['asset_csrf'])?>"><button class="secondary">Log Out</button></form></div>
<?php if($notice):?><p class="success" role="status"><?=approverEscape($notice)?></p><?php endif;?>
<?php if($error):?><p class="error" role="alert"><?=approverEscape($error)?></p><?php endif;?>
<section class="panel"><div class="heading"><h2>Your signature &amp; department chop</h2><span class="pill"><?=isset($latest['combined'])?'Image ready':'Upload combined image'?></span></div>
<p class="muted">Upload <strong>one image containing both your signature and department chop</strong>, arranged as you want them to appear in the signing box. PNG or JPG, up to 2 MB. Transparent PNGs look best. A new upload replaces your current image.</p>
<form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?=approverEscape($_SESSION['asset_csrf'])?>"><input type="hidden" name="MAX_FILE_SIZE" value="2097152">
<label for="combined">Signature &amp; chop image</label>
<div class="signbox combined-box"><div class="signbox-title">DISAHKAN OLEH<br>KETUA JABATAN / UNIT / BAHAGIAN / SEKSYEN / PENSYARAH</div><div class="combined-preview">
<?php if(isset($latest['combined'])):?><img id="combined-preview" alt="Your combined signature and chop" src="image.php?id=<?=(int)$latest['combined']['asset_id']?>"><span id="preview-placeholder" class="muted" hidden></span>
<?php else:?><img id="combined-preview" alt="Selected signature and chop preview" hidden><span id="preview-placeholder" class="muted">Your signature &amp; chop will appear here</span><?php endif;?>
</div><div class="signbox-details">Tandatangan: <?=approverEscape($account['display_name'])?><br>Cop Jawatan: Included in your image<br>Tarikh: Recorded when you approve</div></div>
<input id="combined" name="combined" type="file" accept="image/png,image/jpeg" aria-describedby="upload-help">
<p id="upload-help" class="muted small">The whole image fits inside the box without stretching. This is a placement preview; PDF signing will be connected separately.</p>
<?php if(isset($latest['combined'])):?><p class="muted small">Current image saved: <?=approverEscape($latest['combined']['uploaded_at'])?></p><?php endif;?>
<p id="selection-status" class="muted small" role="status"></p><button>Save Signature &amp; Chop</button>
<p class="muted small">Saving an image does not approve or sign a request.</p></form></section>
<script>
const fileInput = document.getElementById('combined');
let localPreview;
fileInput.addEventListener('change', () => {
    const file = fileInput.files[0];
    if (!file) return;
    if (file.size > 2097152 || !['image/png', 'image/jpeg'].includes(file.type)) {
        document.getElementById('selection-status').textContent = 'Choose a PNG or JPG image up to 2 MB.';
        fileInput.value = '';
        return;
    }
    if (localPreview) URL.revokeObjectURL(localPreview);
    localPreview = URL.createObjectURL(file);
    const image = document.getElementById('combined-preview');
    image.src = localPreview;
    image.hidden = false;
    document.getElementById('preview-placeholder').hidden = true;
    document.getElementById('selection-status').textContent = 'Preview of your selected image. Click Save to store it.';
});
</script>
<?php approverFooter(); ?>
