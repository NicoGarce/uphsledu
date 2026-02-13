<?php
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['success'=>false,'error'=>'Method not allowed']); exit; }
if (!isLoggedIn() || !isSuperAdmin()) { http_response_code(403); echo json_encode(['success'=>false,'error'=>'Unauthorized']); exit; }
if (!CSRF::verify()) { http_response_code(400); echo json_encode(['success'=>false,'error'=>'Invalid CSRF token']); exit; }

$program_id = isset($_POST['program_id']) ? (int)$_POST['program_id'] : 0;
$files = isset($_POST['files']) ? $_POST['files'] : [];
if ($program_id <= 0 || !is_array($files) || empty($files)) { echo json_encode(['success'=>false,'error'=>'program_id and files required']); exit; }

$pdo = getDBConnection();
$stmt = $pdo->prepare('SELECT id FROM library_programs WHERE id = :id');
$stmt->execute([':id'=>$program_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) { echo json_encode(['success'=>false,'error'=>'Program not found']); exit; }

// Use flat library directory
$baseDir = __DIR__ . '/../assets/documents/library/';
$added = []; $skipped = []; $errors = [];
foreach ($files as $fname) {
    $fname = basename($fname);
    $full = $baseDir . $fname;
    if (!is_file($full)) { $errors[] = $fname . ': not found'; continue; }
    // check if already in DB
    $st = $pdo->prepare('SELECT id FROM library_program_pdfs WHERE program_id = :pid AND filename = :fn');
    $st->execute([':pid'=>$program_id,':fn'=>$fname]);
    if ($st->fetch()) { $skipped[] = $fname; continue; }
    // insert
    try {
        $rel = 'assets/documents/library/'.$fname;
        $ins = $pdo->prepare('INSERT INTO library_program_pdfs (program_id, filename, path, uploaded_at) VALUES (:pid,:fn,:path,NOW())');
        $ins->execute([':pid'=>$program_id,':fn'=>$fname,':path'=>$rel]);
        $added[] = $fname;
    } catch (Exception $e) { $errors[] = $fname.': db error'; }
}

echo json_encode(['success'=>count($added)>0,'added'=>$added,'skipped'=>$skipped,'errors'=>$errors]);

?>
