<?php
// fetchAllproject.php
// API endpoint to fetch all projects from the portfolio

header('Content-Type: application/json; charset=utf-8');
// Allow CORS for local testing (adjust in production)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Load DB connection (PDO: $pdo)
require_once __DIR__ . '/../../../../databses/config.php';

/**
 * Create the portfolio_projects table if it doesn't exist
 */
function ensureTableExists($pdo) {
    $sql = "CREATE TABLE IF NOT EXISTS portfolio_projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255),
        description TEXT,
        category VARCHAR(100),
        project_type VARCHAR(50),
        thumbnail VARCHAR(255),
        video_url VARCHAR(255),
        video_poster VARCHAR(255),
        tags TEXT,
        likes INT DEFAULT 0,
        is_featured TINYINT(1) DEFAULT 0,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
}

/**
 * Handle GET request - Fetch all projects
 */
function handleFetch($pdo) {
    // Ensure table exists
    ensureTableExists($pdo);
    
    try {
        $stmt = $pdo->prepare("SELECT id, title, description, category, project_type, thumbnail, video_url, video_poster, tags, likes, is_featured, status, created_at, updated_at FROM `portfolio_projects` WHERE 1 ORDER BY created_at DESC");
        $stmt->execute();
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Add full image URLs for frontend access
        foreach ($projects as &$project) {
            if (!empty($project['thumbnail'])) {
                $project['image_url'] = '/personalPortfolio/Admin/AdminApi/img/' . $project['thumbnail'];
            }
        }
        
        echo json_encode([
            'success' => true, 
            'data' => $projects,
            'count' => count($projects)
        ]);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        exit;
    }
}

// Handle GET request only
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    handleFetch($pdo);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Use GET only.']);
    exit;
}
?>