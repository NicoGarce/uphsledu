<?php
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn() || !isSuperAdmin()) { http_response_code(403); echo json_encode(['success'=>false,'error'=>'Unauthorized']); exit; }

$program_id = isset($_GET['program_id']) ? (int)$_GET['program_id'] : 0;

$pdo = getDBConnection();

// Use flat library directory (no per-program subfolders)
$dir = __DIR__ . '/../assets/documents/library/';
$items = [];
if (is_dir($dir)) {
    $files = scandir($dir);
    foreach ($files as $f) {
        if ($f === '.' || $f === '..') continue;
        $full = $dir . $f;
        if (!is_file($full)) continue;
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if ($ext !== 'pdf') continue;
        $items[] = ['filename'=>$f,'mtime'=>filemtime($full),'path'=>'assets/documents/library/'.$f];
    }
    // sort by mtime desc (newest first)
    usort($items, function($a,$b){ return $b['mtime'] <=> $a['mtime']; });
}

// also fetch existing DB entries to mark attached files
$existing = [];
$st = $pdo->prepare('SELECT filename FROM library_program_pdfs WHERE program_id = :id');
$st->execute([':id'=>$program_id]);
foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) $existing[] = $r['filename'];

echo json_encode(['success'=>true,'files'=>$items,'attached'=>$existing]);

?>
