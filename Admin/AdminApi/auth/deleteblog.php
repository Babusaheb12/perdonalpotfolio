<?php
// Start session to check authentication
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Check if admin is authenticated
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Include database configuration
require_once '../../../databses/config.php';

// Set content type to JSON
header('Content-Type: application/json');

try {
    // Check if request method is POST or DELETE
    if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'DELETE'])) {
        throw new Exception('Only POST or DELETE method is allowed');
    }

    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate required field
    if (empty($input['id'])) {
        throw new Exception("Field 'id' is required");
    }

    // Extract and sanitize input data
    $id = (int)$input['id'];

    // Prepare SQL statement to delete blog post
    $sql = "DELETE FROM blogs WHERE id = ?";
    
    $stmt = $pdo->prepare($sql);
    
    // Execute the statement
    $result = $stmt->execute([$id]);

    if ($result && $stmt->rowCount() > 0) {
        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Blog post deleted successfully',
            'data' => [
                'id' => $id
            ]
        ]);
    } else {
        throw new Exception('Failed to delete blog post or blog post not found');
    }

} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>