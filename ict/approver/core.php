<?php
function approverEscape($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function approverStore() { return rtrim(getenv('ICT_APPROVER_ASSET_DIR') ?: 'C:/xampp/private/ict/approver-assets', '/\\'); }
function approverCsrf() {
    if (!hash_equals($_SESSION['asset_csrf'], (string)($_POST['csrf'] ?? ''))) {
        throw new InvalidArgumentException('Your session expired. Refresh and try again.');
    }
}
function approverImage($file) {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) throw new InvalidArgumentException('Choose an image that is no larger than 2 MB.');
    if ($file['size'] < 1 || $file['size'] > 2 * 1024 * 1024 || !is_uploaded_file($file['tmp_name'])) throw new InvalidArgumentException('Choose an image that is no larger than 2 MB.');
    $size = @getimagesize($file['tmp_name']);
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!$size || !in_array($size[2], [IMAGETYPE_PNG, IMAGETYPE_JPEG], true) || !in_array($mime, ['image/png','image/jpeg'], true)) throw new InvalidArgumentException('Only valid PNG and JPG images are accepted.');
    if ($size[0] > 3000 || $size[1] > 3000 || $size[0] * $size[1] > 6000000) throw new InvalidArgumentException('Use an image up to 3000 pixels per side and 6 million pixels in total.');
    if (!function_exists('imagecreatefromstring')) throw new RuntimeException('GD image support is unavailable.');
    $image = @imagecreatefromstring(file_get_contents($file['tmp_name']));
    if (!$image) throw new InvalidArgumentException('The image could not be read. Try another file.');
    imagealphablending($image, false);
    imagesavealpha($image, true);
    return $image;
}
function approverAssetPath($id, $filename) {
    if ($id < 1 || !preg_match('/^(signature|chop|combined)_[a-f0-9]{32}\.png$/D', $filename)) throw new RuntimeException('Invalid image reference.');
    return approverStore() . '/approver_' . (int)$id . '/' . $filename;
}
function approverHeader($title) {
    header('Cache-Control: no-store');
    header('Referrer-Policy: no-referrer');
    header('X-Content-Type-Options: nosniff');
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.approverEscape($title).' | ePinjamICT</title><link rel="stylesheet" href="style.css"></head><body><header><a class="brand" href="signatures.php">ePinjam<span>ICT</span></a><nav class="portal-nav"><a href="approvals.php">My Approvals</a><a href="signatures.php">Signature &amp; Chop</a></nav></header><main>';
}
function approverFooter() { echo '</main></body></html>'; }

function removePreviousApproverImage($id, $filename) {
    try {
        $path = approverAssetPath($id, $filename);
        if (!is_file($path)) return;
        $resolved = realpath($path);
        $directory = realpath(approverStore() . '/approver_' . (int)$id);
        if (!$resolved || !$directory || strcasecmp(dirname($resolved), $directory) !== 0) {
            throw new RuntimeException('Image path is outside the expected approver folder.');
        }
        if (!unlink($resolved)) throw new RuntimeException('Could not remove replaced image.');
    } catch (Throwable $e) {
        error_log('Approver image cleanup: ' . $e->getMessage());
    }
}
