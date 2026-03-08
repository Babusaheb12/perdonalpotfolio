<?php
// updateProject.php
// API endpoint to update an existing project in the `portfolio_projects` table.

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

// Define upload directory
define('UPLOAD_DIR', __DIR__ . '/../img/');

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

// Retrieve form data
$title = $input['title'] ?? $_POST['title'] ?? '';
$description = $input['description'] ?? $_POST['description'] ?? '';
$category = $input['category'] ?? $_POST['category'] ?? '';
$project_type = $input['project_type'] ?? $_POST['project_type'] ?? 'image';
$video_url = $input['video_url'] ?? $_POST['video_url'] ?? '';
$video_description = $input['video_description'] ?? $_POST['video_description'] ?? '';
$tags = $input['tags'] ?? $_POST['tags'] ?? '';
$is_featured = isset($input['is_featured']) || isset($_POST['is_featured']) ? filter_var($input['is_featured'] ?? $_POST['is_featured'], FILTER_VALIDATE_BOOLEAN) : 0;
$status = $input['status'] ?? $_POST['status'] ?? 'active';

// Basic Validation
if (empty($title) || empty($category)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Title and Category are required.']);
    exit;
}

$validCategories = ['development', 'application', 'photoshop', 'video', 'motion-graphics', 'animation', 'strategy'];
if (!in_array($category, $validCategories)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid category.']);
    exit;
}

$validProjectTypes = ['image', 'video'];
if (!in_array($project_type, $validProjectTypes)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Invalid project type.']);
    exit;
}

try {
    // Check if project exists
    $checkSql = "SELECT id, thumbnail FROM portfolio_projects WHERE id = :id";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([':id' => (int)$projectId]);
    
    $existingProject = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$existingProject) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Project not found.']);
        exit;
    }
    
    // Handle Thumbnail Upload (if provided)
    $thumbnail = $existingProject['thumbnail']; // Default to existing thumbnail
    $oldThumbnailPath = null;
    
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        // Validate uploaded file
        $allowedTypes = [
            'image/jpeg',
            'image/pjpeg',
            'image/jpg',
            'image/png',
            'image/x-png',
            'image/gif',
            'image/webp'
        ];
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        $file = $_FILES['thumbnail'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Upload error: ' . $file['error']]);
            exit;
        }
        
        $detectedType = null;
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            if ($finfo && is_uploaded_file($file['tmp_name'])) {
                $detectedType = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);
            }
        }
        $mimeToCheck = $detectedType ?: ($file['type'] ?? '');
        $ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
        
        // Basic check if MIME or Extension is allowed
        $mimeOk = $mimeToCheck && in_array($mimeToCheck, $allowedTypes);
        $extOk = $ext && in_array($ext, $allowedExts);
        
        if (!$mimeOk && !$extOk) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Allowed: JPEG, PNG, GIF, WebP']);
            exit;
        }
        
        if ($file['size'] > $maxSize) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'File too large. Maximum size is 5MB']);
            exit;
        }
        
        // Generate unique filename
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $timestamp = date('Ymd_His');
        $random = bin2hex(random_bytes(4));
        $newThumbnail = "project_{$timestamp}_{$random}.{$extension}";
        
        $destination = UPLOAD_DIR . $newThumbnail;
        
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Set new thumbnail and mark old one for deletion
            $thumbnail = $newThumbnail;
            $oldThumbnailPath = UPLOAD_DIR . $existingProject['thumbnail'];
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file.']);
            exit;
        }
    }
    
    // Update the project in the database
    $sql = "UPDATE portfolio_projects 
            SET title = :title, 
                description = :description, 
                category = :category, 
                project_type = :project_type, 
                thumbnail = :thumbnail, 
                video_url = :video_url, 
                video_poster = :video_description, 
                tags = :tags, 
                is_featured = :is_featured, 
                status = :status
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        ':id' => (int)$projectId,
        ':title' => $title,
        ':description' => $description,
        ':category' => $category,
        ':project_type' => $project_type,
        ':thumbnail' => $thumbnail,
        ':video_url' => $video_url,
        ':video_description' => $video_description, // Using video_poster column as per database schema
        ':tags' => $tags,
        ':is_featured' => $is_featured ? 1 : 0,
        ':status' => $status
    ]);
    
    if ($result) {
        // Delete old thumbnail if a new one was uploaded
        if ($oldThumbnailPath && file_exists($oldThumbnailPath)) {
            @unlink($oldThumbnailPath);
        }
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Project updated successfully.',
            'data' => [
                'id' => $projectId,
                'thumbnail_url' => './AdminApi/img/' . $thumbnail
            ]
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to update project in database.']);
    }
    
} catch (PDOException $e) {
    error_log('Update project error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}