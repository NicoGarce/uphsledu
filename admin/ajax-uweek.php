<?php
// Moved to /uweek/admin/ajax.php – proxy for backward compatibility
define('SKIP_SECURITY_HEADERS', true);
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';
header('Content-Type: application/json');
if (!isLoggedIn() || (!isSuperAdmin() && !isAdmin())) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized – please use /uweek/admin/']);
    exit;
}
$_POST['action'] = $_POST['action'] ?? '';
// Forward to new handler
include __DIR__ . '/../uweek/admin/ajax.php';
