<?php
// Public tracker for UWeek live viewers – no auth required
define('SKIP_SECURITY_HEADERS', true);
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/includes/functions.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$pageKey = $_POST['page'] ?? $_GET['page'] ?? '';
$pageKey = trim($pageKey);
if ($pageKey === '') {
    // Infer from referer or default to brackets
    $pageKey = 'brackets';
}
// Only allow POST/GET
if (!in_array($_SERVER['REQUEST_METHOD'], ['POST','GET'])) {
    http_response_code(405);
    echo json_encode(['success'=>false,'error'=>'Method not allowed']);
    exit;
}

trackUWeekViewer($pageKey);

// Optionally return live count for public badge (without exposing IPs)
$counts = getUWeekLiveCounts(2);
echo json_encode(['success'=>true, 'page'=>$pageKey, 'counts'=>$counts]);
