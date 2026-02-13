<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success'=>false,'error'=>'Method not allowed']);
    exit;
}

if (!isLoggedIn() || !isSuperAdmin()) {
    http_response_code(403);
    echo json_encode(['success'=>false,'error'=>'Unauthorized']);
    exit;
}

if (!CSRF::verify()) {
    http_response_code(400);
    echo json_encode(['success'=>false,'error'=>'Invalid CSRF token']);
    exit;
}

$pdf_id = isset($_POST['pdf_id']) ? (int)$_POST['pdf_id'] : 0;
if ($pdf_id <= 0) {
    echo json_encode(['success'=>false,'error'=>'Invalid pdf id']); exit;
}

$pdo = getDBConnection();
$stmt = $pdo->prepare('SELECT id, program_id, filename, path FROM library_program_pdfs WHERE id = :id');
$stmt->execute([':id'=>$pdf_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    echo json_encode(['success'=>false,'error'=>'PDF not found']); exit;
}

// Do NOT delete the physical file from disk. Only remove the DB record.
$del = $pdo->prepare('DELETE FROM library_program_pdfs WHERE id = :id');
$del->execute([':id'=>$pdf_id]);

echo json_encode(['success'=>true,'message'=>'Removed']);
