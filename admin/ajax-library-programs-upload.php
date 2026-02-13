<?php
/**
 * Library Programs PDF Upload AJAX Endpoint
 * Accepts multiple PDF files and stores them under
 * assets/documents/library/programs/{program-slug}/
 */

require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

// Only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

// Permission check
if (!isLoggedIn() || !isSuperAdmin()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

// CSRF
if (!CSRF::verify()) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
    exit;
}

// Accept either program_id or program slug
$pdo = getDBConnection();
$program_id = isset($_POST['program_id']) ? (int)$_POST['program_id'] : 0;
$program_slug = '';
if ($program_id > 0) {
    $stmt = $pdo->prepare('SELECT slug FROM library_programs WHERE id = :id');
    $stmt->execute([':id'=>$program_id]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$r) {
        echo json_encode(['success'=>false,'error'=>'Program not found']); exit;
    }
    $program_slug = $r['slug'];
} else {
    $program_slug = $_POST['program'] ?? '';
    $program_slug = preg_replace('/[^a-z0-9\-]/', '', strtolower($program_slug));
    if (empty($program_slug)) {
        echo json_encode(['success'=>false,'error'=>'Program slug or id required']); exit;
    }
    // ensure program exists or create
    $stmt = $pdo->prepare('SELECT id FROM library_programs WHERE slug = :slug');
    $stmt->execute([':slug'=>$program_slug]);
    $r = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($r) {
        $program_id = (int)$r['id'];
    } else {
        $defaultTitle = ucwords(str_replace('-', ' ', $program_slug));
        $ins = $pdo->prepare('INSERT INTO library_programs (slug,title,created_at,updated_at) VALUES (:slug,:title,NOW(),NOW())');
        $ins->execute([':slug'=>$program_slug,':title'=>$defaultTitle]);
        $program_id = (int)$pdo->lastInsertId();
    }
}

// accept files from 'files' or 'pdfs'
$filesField = null;
if (isset($_FILES['files'])) $filesField = $_FILES['files'];
elseif (isset($_FILES['pdfs'])) $filesField = $_FILES['pdfs'];
else {
    echo json_encode(['success'=>false,'error'=>'No files uploaded']); exit;
}

$count = is_array($filesField['name']) ? count($filesField['name']) : 0;
if ($count === 0) { echo json_encode(['success'=>false,'error'=>'No files provided']); exit; }

$baseDir = __DIR__ . '/../assets/documents/library/programs/' . $program_slug . '/';
if (!is_dir($baseDir)) { if (!mkdir($baseDir,0755,true)) { echo json_encode(['success'=>false,'error'=>'Failed to create program directory']); exit; } }

$saved = [];
$errors = [];
for ($i=0;$i<$count;$i++) {
    if ($filesField['error'][$i] !== UPLOAD_ERR_OK) { $errors[] = "Upload error index {$i}"; continue; }
    $original = $filesField['name'][$i];
    $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    if ($ext !== 'pdf') { $errors[] = "{$original}: invalid extension"; continue; }
    $size = (int)$filesField['size'][$i];
    if ($size > 50 * 1024 * 1024) { $errors[] = "{$original}: too large"; continue; }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $filesField['tmp_name'][$i]);
    finfo_close($finfo);
    if ($mime !== 'application/pdf') { $errors[] = "{$original}: invalid mime {$mime}"; continue; }

    $safeName = preg_replace('/[^a-zA-Z0-9_\-\.]/','_',pathinfo($original, PATHINFO_FILENAME));
    $final = $safeName . '.' . $ext;
    $target = $baseDir . $final;
    $counter = 1;
    while (file_exists($target)) { $final = $safeName . '_' . $counter . '.' . $ext; $target = $baseDir . $final; $counter++; }
    if (!move_uploaded_file($filesField['tmp_name'][$i], $target)) { $errors[] = "{$original}: move failed"; continue; }
    @chmod($target, 0644);
    $saved[] = $final;
    // Spawn a background job to generate a JPEG thumbnail of the first PDF page (non-blocking)
    try {
        $thumbBase = $baseDir . pathinfo($final, PATHINFO_FILENAME) . '.jpg';
        $phpBinary = defined('PHP_BINARY') ? PHP_BINARY : 'php';
        $script = __DIR__ . '/generate-pdf-thumb.php';
        $cmd = escapeshellarg($phpBinary) . ' ' . escapeshellarg($script) . ' ' . escapeshellarg($target) . ' ' . escapeshellarg($thumbBase);
        if (stripos(PHP_OS, 'WIN') === 0) {
            // Windows: use start /B to run in background
            @pclose(@popen('start /B ' . $cmd, 'r'));
        } else {
            // Unix-like: append ampersand
            @exec($cmd . ' > /dev/null 2>&1 &');
        }
    } catch (Exception $e) {
        error_log('thumbnail spawn failed: ' . $e->getMessage());
    }
    try {
        $relPath = 'assets/documents/library/programs/' . $program_slug . '/' . $final;
        $ins = $pdo->prepare('INSERT INTO library_program_pdfs (program_id, filename, path, uploaded_at) VALUES (:program_id, :filename, :path, NOW())');
        $ins->execute([':program_id'=>$program_id,':filename'=>$final,':path'=>$relPath]);
    } catch (Exception $e) {
        // ignore DB insert error but log
        error_log('library upload db insert: '.$e->getMessage());
    }
}

$result = ['success' => count($saved) > 0, 'saved'=>$saved, 'errors'=>$errors];
if (!empty($errors)) $result['error'] = implode('; ', $errors);
if (count($saved)>0) $result['message'] = 'Uploaded '.count($saved).' file(s)';

echo json_encode($result);
