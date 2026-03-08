<?php
// deleteProject.php
// API endpoint to delete a project from the `portfolio_projects` table.

header('Content-Type: application/json; charset=utf-8');
// Allow CORS for local testing (adjust in production)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Load DB connection (PDO: $pdo)
require_once __DIR__ . '/../../../databses/config.php';

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Use POST.']);
    exit;
}

// Get the JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Get project ID from request
$projectId = $input['id'] ?? $_POST['id'] ?? null;

// Validate project ID
if (!$projectId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Project ID is required.']);
    exit;
}

// Validate project ID is numeric
if (!is_numeric($projectId)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid project ID.']);
    exit;
}

try {
    // First, get the project to retrieve the thumbnail filename
    $selectSql = "SELECT thumbnail FROM portfolio_projects WHERE id = :id";
    $selectStmt = $pdo->prepare($selectSql);
    $selectStmt->execute([':id' => (int)$projectId]);
    
    $project = $selectStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$project) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Project not found.']);
        exit;
    }
    
    // Delete the project from the database
    $deleteSql = "DELETE FROM portfolio_projects WHERE id = :id";
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteResult = $deleteStmt->execute([':id' => (int)$projectId]);
    
    if ($deleteResult) {
        // Attempt to delete the thumbnail file from the server
        $thumbnailPath = __DIR__ . '/../img/' . $project['thumbnail'];
        if (file_exists($thumbnailPath)) {
            @unlink($thumbnailPath); // Use @ to suppress errors if file doesn't exist
        }
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Project deleted successfully.'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete project from database.'
        ]);
    }
    
} catch (PDOException $e) {
    error_log('Delete project error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}