<?php
// fetchContactUs.php
// API endpoint to fetch contact leads (id, name, email, subject, status, created_at).

header('Content-Type: application/json; charset=utf-8');
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
    $status = isset($_GET['status']) && $_GET['status'] !== 'all' ? $_GET['status'] : null;

    if ($status) {
        $stmt = $pdo->prepare('SELECT id, name, phone_number, email, subject, message, status, created_at FROM contact_messages WHERE status = :status ORDER BY created_at DESC');
        $stmt->execute([':status' => $status]);
    } else {
        $stmt = $pdo->query('SELECT id, name, phone_number, email, subject, message, status, created_at FROM contact_messages ORDER BY created_at DESC');
    }

    $rows = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $rows]);
    exit;

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    exit;
}
?>