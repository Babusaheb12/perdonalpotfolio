<?php
// showprofileAndlogo.php
// API endpoint to fetch active profile images and logos

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
 * Create the profile_images table if it doesn't exist
 */
function ensureTableExists($pdo) {
    $sql = "CREATE TABLE IF NOT EXISTS profile_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        image_path VARCHAR(255) NOT NULL,
        status ENUM('active','inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
}

/**
 * Handle GET request - Fetch active images
 */
function handleFetch($pdo) {
    // Ensure table exists
    ensureTableExists($pdo);
    
    try {
        $result = ['profile' => null, 'logo' => null];
        
        // Fetch latest active profile image
        $stmtP = $pdo->prepare("SELECT id, image_path, status, created_at, updated_at FROM profile_images WHERE image_path LIKE 'profile_%' AND status = 'active' ORDER BY created_at DESC LIMIT 1");
        $stmtP->execute();
        $rowP = $stmtP->fetch();
        if ($rowP) {
        $rowP['url'] = '/personalPortfolio/Admin/AdminApi/img/' . $rowP['image_path'];
            $result['profile'] = $rowP;
        }
        
        // Fetch latest active logo image
        $stmtL = $pdo->prepare("SELECT id, image_path, status, created_at, updated_at FROM profile_images WHERE image_path LIKE 'logo_%' AND status = 'active' ORDER BY created_at DESC LIMIT 1");
        $stmtL->execute();
        $rowL = $stmtL->fetch();
        if ($rowL) {
            $rowL['url'] = '/personalPortfolio/Admin/AdminApi/img/' . $rowL['image_path'];
            $result['logo'] = $rowL;
        }
        
        echo json_encode(['success' => true, 'data' => $result]);
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