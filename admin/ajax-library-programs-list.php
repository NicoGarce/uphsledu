<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn() || !isSuperAdmin()) {
    http_response_code(403);
    echo json_encode(['success'=>false,'error'=>'Unauthorized']);
    exit;
}

$program_id = isset($_GET['program_id']) ? (int)$_GET['program_id'] : 0;
if ($program_id <= 0) {
    echo json_encode(['success'=>false,'error'=>'Invalid program id']); exit;
}

$pdo = getDBConnection();
$stmt = $pdo->prepare('SELECT id, filename, path, uploaded_at FROM library_program_pdfs WHERE program_id = :pid ORDER BY uploaded_at DESC');
$stmt->execute([':pid'=>$program_id]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success'=>true,'files'=>$rows]);
