<?php
// fetchProjects.php
// API endpoint to fetch all projects from the `portfolio_projects` table.

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
require_once __DIR__ . '/../../../databses/config.php';

try {
    // Prepare and execute query to fetch all projects
    $sql = "SELECT * FROM portfolio_projects ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Format the response
    $formattedProjects = [];
    foreach ($projects as $project) {
        $formattedProjects[] = [
            'id' => (int)$project['id'],
            'title' => $project['title'],
            'description' => $project['description'],
            'category' => $project['category'],
            'project_type' => $project['project_type'],
            'thumbnail' => '/personalPortfolio/Admin/AdminApi/img/' . $project['thumbnail'], // Full path to image
            'video_url' => $project['video_url'],
            'video_description' => $project['video_description'], // Fixed field name
            'tags' => $project['tags'],
            'likes' => (int)$project['likes'],
            'is_featured' => (bool)$project['is_featured'],
            'status' => $project['status'],
            'created_at' => $project['created_at']
        ];
    }
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $formattedProjects,
        'count' => count($formattedProjects)
    ]);
    
} catch (PDOException $e) {
    error_log('Fetch projects error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}