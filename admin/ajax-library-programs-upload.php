<?php
/**
 * Library Programs Thumbnail Upload AJAX Endpoint
 * Accepts one image file (thumbnail) and updates the program's `image` column.
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

if (!isLoggedIn() || !isSuperAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if (!CSRF::verify()) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
    exit;
}

$pdo = getDBConnection();
$program_id = isset($_POST['program_id']) ? (int)$_POST['program_id'] : 0;
if ($program_id <= 0) { echo json_encode(['success'=>false,'error'=>'program_id required']); exit; }

$stmt = $pdo->prepare('SELECT slug FROM library_programs WHERE id = :id');
$stmt->execute([':id'=>$program_id]);
$r = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$r) { echo json_encode(['success'=>false,'error'=>'Program not found']); exit; }
$slug = $r['slug'];

// Diagnostic logging (temporary): record request info to system temp for debugging upload issues

// Accept either a URL (image_url) or an uploaded file 'thumbnail'
$imageUrl = trim($_POST['image_url'] ?? '');
if (!empty($imageUrl)) {
    // store as-provided (trusted admin-entered URL) or could validate further
    $rel = Validator::sanitize($imageUrl, 'string');
    if ($rel && strpos($rel, '/') !== 0 && stripos($rel, 'http') !== 0) {
        $rel = '/' . ltrim($rel, '/');
    }
    $up = $pdo->prepare('UPDATE library_programs SET image = :img, updated_at = NOW() WHERE id = :id');
    $up->execute([':img'=>$rel,':id'=>$program_id]);
    echo json_encode(['success'=>true,'image'=>$rel]); exit;
}

// Accept either 'thumbnail' or 'image_file' (modal uses image_file)
if (!isset($_FILES['thumbnail']) && !isset($_FILES['image_file'])) { echo json_encode(['success'=>false,'error'=>'No thumbnail file uploaded']); exit; }
$file = isset($_FILES['thumbnail']) ? $_FILES['thumbnail'] : $_FILES['image_file'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    $errMap = [
        UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
    ];
    $code = $file['error'];
    $msg = $errMap[$code] ?? 'Unknown upload error';
    echo json_encode(['success'=>false,'error'=>'Upload error: ' . $msg, 'code'=>$code]); exit;
}

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
// allow common image types (gif included)
if (!in_array($ext, ['jpg','jpeg','png','webp','gif'])) { echo json_encode(['success'=>false,'error'=>'Invalid image type: .' . $ext]); exit; }

$baseDir = __DIR__ . '/../assets/images/support-services/college-library/programs/' . $slug . '/';
if (!is_dir($baseDir)) { if (!@mkdir($baseDir,0755,true)) { echo json_encode(['success'=>false,'error'=>'Failed to create target directory']); exit; } }

$safe = preg_replace('/[^a-zA-Z0-9_\-\.]/','_',pathinfo($file['name'], PATHINFO_FILENAME));
$final = $safe . '.' . $ext;
$target = $baseDir . $final;
$counter = 1; while (file_exists($target)) { $final = $safe . '_' . $counter . '.' . $ext; $target = $baseDir . $final; $counter++; }
if (!move_uploaded_file($file['tmp_name'], $target)) {
    $err = error_get_last();
    $msg = 'Move failed';
    if ($err && isset($err['message'])) $msg .= ': ' . $err['message'];
    echo json_encode(['success'=>false,'error'=>$msg]); exit;
}
// verify file exists
if (!file_exists($target)) {
    echo json_encode(['success'=>false,'error'=>'File not found after move']); exit;
}
@chmod($target, 0644);

$dbRelPath = 'assets/images/support-services/college-library/programs/' . $slug . '/' . $final;
// store DB-friendly relative path (no leading slash)
$up = $pdo->prepare('UPDATE library_programs SET image = :img, updated_at = NOW() WHERE id = :id');
$up->execute([':img'=>$dbRelPath,':id'=>$program_id]);

// Build public URL that respects app subdirectory (e.g., /uphsledu) and avoid duplicate app dir
$scriptDir = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/'); // e.g. /uphsledu or ''
$prefix = ($scriptDir !== '') ? '/' . ltrim($scriptDir, '/') . '/' : '/';
$imgRel = ltrim($dbRelPath, '/');
if ($scriptDir !== '' && stripos($imgRel, ltrim($scriptDir, '/')) === 0) {
    $imgRel = preg_replace('#^' . preg_quote(ltrim($scriptDir, '/'), '#') . '/#i', '', $imgRel);
}
$publicPath = $prefix . $imgRel;

// Return more info for client-side verification (non-persistent, helpful for debugging)
echo json_encode([
    'success' => true,
    'image' => $publicPath,
    'db_image' => $dbRelPath,
    'target' => $target
]);
