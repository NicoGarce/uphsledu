<?php
define('SKIP_SECURITY_HEADERS', true);
require_once '../../app/config/database.php';
require_once '../../app/includes/functions.php';

header('Content-Type: application/json');

if (!isLoggedIn() || (!isSuperAdmin() && !isAdmin())) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}
if (!CSRF::verify()) {
    http_response_code(419);
    echo json_encode(['success' => false, 'error' => 'CSRF token mismatch']);
    exit;
}

$pdo = getDBConnection();
$action = $_POST['action'] ?? '';

try {
    if ($action === 'toggle_event') {
        $eventId = (int)($_POST['event_id'] ?? 0);
        $isEnabled = isset($_POST['is_enabled']) ? (int)$_POST['is_enabled'] : null;
        if ($eventId <= 0 || $isEnabled === null) {
            echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
            exit;
        }
        $isEnabled = $isEnabled ? 1 : 0;
        $stmt = $pdo->prepare("UPDATE uweek_events SET is_enabled = ? WHERE id = ?");
        $stmt->execute([$isEnabled, $eventId]);
        echo json_encode(['success' => true, 'is_enabled' => $isEnabled]);
        exit;
    } elseif ($action === 'toggle_category') {
        $catId = (int)($_POST['category_id'] ?? 0);
        $isEnabled = isset($_POST['is_enabled']) ? (int)$_POST['is_enabled'] : null;
        if ($catId <= 0 || $isEnabled === null) {
            echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
            exit;
        }
        $isEnabled = $isEnabled ? 1 : 0;
        $stmt = $pdo->prepare("UPDATE uweek_categories SET is_enabled = ? WHERE id = ?");
        $stmt->execute([$isEnabled, $catId]);
        echo json_encode(['success' => true, 'is_enabled' => $isEnabled]);
        exit;
    } elseif ($action === 'bulk_toggle_events') {
        $catId = (int)($_POST['category_id'] ?? 0);
        $bulk = $_POST['bulk'] ?? '';
        if ($catId <= 0 || !in_array($bulk, ['enable','disable'])) {
            echo json_encode(['success' => false, 'error' => 'Invalid parameters']);
            exit;
        }
        $enable = $bulk === 'enable' ? 1 : 0;
        $stmt = $pdo->prepare("UPDATE uweek_events SET is_enabled = ? WHERE category_id = ?");
        $stmt->execute([$enable, $catId]);
        echo json_encode(['success' => true, 'is_enabled' => $enable, 'category_id' => $catId]);
        exit;
    } elseif ($action === 'get_live_counts') {
        $counts = getUWeekLiveCounts(2);
        echo json_encode(['success' => true, 'counts' => $counts]);
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Unknown action']);
        exit;
    }
} catch (Exception $e) {
    error_log('ajax-uweek error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Server error']);
}
