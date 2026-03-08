<?php
// deleteContactUs.php
// API endpoint to delete a contact lead.

header('Content-Type: application/json; charset=utf-8');
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

try {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!isset($data['id'])) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Missing lead ID']);
        exit;
    }

    $id = (int) $data['id'];

    $stmt = $pdo->prepare('DELETE FROM contact_messages WHERE id = :id');
    $result = $stmt->execute([':id' => $id]);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Lead deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to delete lead']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    exit;
}
?>