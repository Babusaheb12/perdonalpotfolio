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
    // Check if request method is GET
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Only GET method is allowed');
    }

    // Prepare SQL statement to fetch all blog posts
    $sql = "SELECT id, title, subtitle, slug, category, author, thumbnail, video_url, banner_image, short_description, full_description, read_time, status, created_at, updated_at FROM blogs ORDER BY created_at DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return success response
    echo json_encode([
        'success' => true,
        'data' => $blogs,
        'count' => count($blogs)
    ]);

} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>