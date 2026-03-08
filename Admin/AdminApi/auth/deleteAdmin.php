<?php
// deleteAdmin.php
// API endpoint to delete an admin record by id.

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
if ($id <= 0) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Valid id is required']);
    exit;
}

try {
    // Ensure admin exists
    $check = $pdo->prepare('SELECT id, email FROM admins WHERE id = :id LIMIT 1');
    $check->execute([':id' => $id]);
    $row = $check->fetch();
    if (!$row) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Admin not found']);
        exit;
    }

    // Delete admin
    $del = $pdo->prepare('DELETE FROM admins WHERE id = :id');
    $del->execute([':id' => $id]);

    echo json_encode(['success' => true, 'message' => 'Admin deleted', 'deleted' => ['id' => $id, 'email' => $row['email']]]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    // In production log the error and return a generic message
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    exit;
}

?>
