<?php
require_once '../app/config/database.php';
require_once '../app/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn() || !isSuperAdmin()) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

if (!CSRF::verify()) {
    echo json_encode(['success' => false, 'error' => 'Security token mismatch']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');

if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid program ID']);
    exit;
}

try {
    $pdo = getDBConnection();
    $up = $pdo->prepare('UPDATE library_programs SET title = :title, description = :description, updated_at = NOW() WHERE id = :id');
    $up->execute([':title' => $title, ':description' => $description, ':id' => $id]);

    echo json_encode(['success' => true, 'message' => 'Program updated successfully']);
    exit;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    exit;
}

?>
<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

if (!isLoggedIn() || !isSuperAdmin()) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

if (!CSRF::verify()) {
    echo json_encode(['success' => false, 'error' => 'Security token mismatch']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$title = Validator::sanitize($_POST['title'] ?? '', 'string');
$description = Validator::sanitize($_POST['description'] ?? '', 'string');

if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid program id']);
    exit;
}

try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare('SELECT id FROM library_programs WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        echo json_encode(['success' => false, 'error' => 'Program not found']);
        exit;
    }

    $up = $pdo->prepare('UPDATE library_programs SET title = :title, description = :description, updated_at = NOW() WHERE id = :id');
    $up->execute([':title' => $title, ':description' => $description, ':id' => $id]);

    echo json_encode(['success' => true, 'message' => 'Program updated']);
    exit;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
    exit;
}

?>
