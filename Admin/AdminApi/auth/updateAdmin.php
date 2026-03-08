<?php
// updateAdmin.php
// API endpoint to update only the `status` field of an admin record.

header('Content-Type: application/json; charset=utf-8');
// Allow CORS for local testing (adjust in production)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Use POST.']);
    exit;
}

require_once __DIR__ . '/../../../databses/config.php';

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON body']);
    exit;
}

$id = isset($input['id']) ? (int)$input['id'] : 0;
$status = isset($input['status']) ? trim($input['status']) : '';

// Validation
if ($id <= 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Valid id is required.']);
    exit;
}

$allowed = ['active', 'inactive'];
if (!in_array($status, $allowed, true)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid status. Allowed: active, inactive.']);
    exit;
}

try {
    // Ensure admin exists
    $check = $pdo->prepare('SELECT id FROM admins WHERE id = :id LIMIT 1');
    $check->execute([':id' => $id]);
    $found = $check->fetch();
    if (!$found) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Admin not found']);
        exit;
    }

    // Update status
    $update = $pdo->prepare('UPDATE admins SET status = :status WHERE id = :id');
    $update->execute([':status' => $status, ':id' => $id]);

    // Return updated row (id, email, status)
    $stmt = $pdo->prepare('SELECT id, email, status FROM admins WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch();

    echo json_encode(['success' => true, 'message' => 'Status updated', 'data' => $row]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    // In production, log $e->getMessage() and return a generic message
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    exit;
}

?>
