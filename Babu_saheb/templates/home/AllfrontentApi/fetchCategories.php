<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../../../databses/config.php';

try {
    // Fetch all active categories
    $stmt = $pdo->prepare("SELECT id, name, description FROM categories WHERE status = 'active' ORDER BY created_at DESC");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare response data
    $response = [
        'data' => $categories,
        'success' => true,
        'message' => 'Categories fetched successfully'
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>
