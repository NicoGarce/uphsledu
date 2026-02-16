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
$image = Validator::sanitize($_POST['image'] ?? '', 'string');
$link = Validator::sanitize($_POST['link'] ?? '', 'url');

if ($id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid program id']);
    exit;
}

try {
    $pdo = getDBConnection();
    // Ensure program exists
    $stmt = $pdo->prepare('SELECT id FROM library_programs WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        echo json_encode(['success' => false, 'error' => 'Program not found']);
        exit;
    }

    $fields = ['title' => $title, 'description' => $description, 'id' => $id];
    $sqlParts = ['title = :title', 'description = :description', 'updated_at = NOW()'];
    if (!empty($image)) { $sqlParts[] = 'image = :image'; $fields['image'] = $image; }
    if (!empty($link)) { $sqlParts[] = 'link = :link'; $fields['link'] = $link; }

    $sql = 'UPDATE library_programs SET ' . implode(', ', $sqlParts) . ' WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($fields);

    // Return the updated program row so client can refresh UI
    $q = $pdo->prepare('SELECT id, slug, title, description, image, link FROM library_programs WHERE id = :id LIMIT 1');
    $q->execute([':id' => $id]);
    $program = $q->fetch(PDO::FETCH_ASSOC);
    if (!empty($program['image']) && strpos($program['image'], '/') !== 0 && stripos($program['image'], 'http') !== 0) {
        $program['image'] = '/' . $program['image'];
    }

    echo json_encode(['success' => true, 'message' => 'Program updated', 'program' => $program]);
    exit;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
    exit;
}

?>
