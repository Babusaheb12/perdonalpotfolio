<?php
// fetchAdmin.php
// API endpoint to fetch admins (id, email, status). Supports:
//  - GET /fetchAdmin.php           -> list all admins
//  - GET /fetchAdmin.php?id=123    -> get admin with id=123

header('Content-Type: application/json; charset=utf-8');
// Allow CORS for local testing (adjust in production)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Use GET.']);
    exit;
}

require_once __DIR__ . '/../../../databses/config.php';

try {
    // Accept id via query string
    $id = isset($_GET['id']) && $_GET['id'] !== '' ? (int) $_GET['id'] : null;

    if ($id) {
        $stmt = $pdo->prepare('SELECT id, email, status FROM admins WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Admin not found']);
            exit;
        }

        echo json_encode(['success' => true, 'data' => $row]);
        exit;
    }

    // List all admins
    $stmt = $pdo->query('SELECT id, email, status FROM admins ORDER BY id DESC');
    $rows = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $rows]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    // In production, log $e->getMessage() and return a generic message
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    exit;
}

?>
