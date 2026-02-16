<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn() || !isSuperAdmin()) {
    http_response_code(403);
    echo json_encode(['success'=>false,'error'=>'Unauthorized']);
    exit;
}

// Return program metadata (id, title, description, image, link) for editing UI
$program_id = isset($_GET['program_id']) ? (int)$_GET['program_id'] : 0;
if ($program_id <= 0) {
    echo json_encode(['success'=>false,'error'=>'Invalid program id']); exit;
}

$pdo = getDBConnection();
$stmt = $pdo->prepare('SELECT id, slug, title, description, image, link FROM library_programs WHERE id = :id LIMIT 1');
$stmt->execute([':id'=>$program_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    echo json_encode(['success'=>false,'error'=>'Program not found']); exit;
}

// Normalize image path to include app subdirectory so admin previews resolve correctly
if (!empty($row['image']) && stripos($row['image'], 'http') === false) {
    // scriptDir like '/uphsledu' or '' for root
    $scriptDir = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
    $prefix = ($scriptDir !== '') ? '/' . ltrim($scriptDir, '/') . '/' : '/';
    // stored image may already contain a leading app directory (from older rows) — normalize to avoid duplication
    $imgRel = ltrim($row['image'], '/');
    if ($scriptDir !== '' && stripos($imgRel, ltrim($scriptDir, '/')) === 0) {
        $imgRel = preg_replace('#^' . preg_quote(ltrim($scriptDir, '/'), '#') . '/#i', '', $imgRel);
    }
    $row['image'] = $prefix . $imgRel;
}

echo json_encode(['success'=>true,'program'=>$row]);
