<?php
// Enable CORS for frontend requests
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include database configuration
require_once '../../../../databses/config.php';

try {
    // Prepare SQL query to fetch specific columns from awesome_clients table
    $stmt = $pdo->prepare("SELECT id, category, client_name, logo_path FROM awesome_clients");
    $stmt->execute();
    
    // Fetch all results
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return success response with data
    echo json_encode([
        'success' => true,
        'data' => $clients,
        'count' => count($clients)
    ]);
    
} catch (PDOException $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Handle any other exceptions
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?>