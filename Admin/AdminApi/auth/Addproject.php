<?php
// Addproject.php
// API endpoint to add a new project into the `portfolio_projects` table.

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
define('UPLOAD_DIR', __DIR__ . '/../img/'); /// this is a correct path

// Ensure upload directory exists and is writable
function ensureUploadDirWritable() {
    if (!is_dir(UPLOAD_DIR)) {
        @mkdir(UPLOAD_DIR, 0777, true);
    }
    if (!is_writable(UPLOAD_DIR)) {
        @chmod(UPLOAD_DIR, 0777);
    }
    return is_dir(UPLOAD_DIR) && is_writable(UPLOAD_DIR);
}

// Logging helper
function api_log_file() {
    return __DIR__ . '/../../../databses/api.log';
}

function log_event(array $event) {
    $event['ts'] = date('c');
    $line = json_encode($event, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    @file_put_contents(api_log_file(), $line . PHP_EOL, FILE_APPEND | LOCK_EX);
}

// Get upload error message
function getUploadErrorMessage($errorCode) {
    $errors = [
        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'Upload stopped by extension',
    ];
    return $errors[$errorCode] ?? 'Unknown error';
}

// Validate uploaded file
function validateUpload($file) {
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
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'message' => 'Upload error: ' . getUploadErrorMessage($file['error'])];
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
        return ['success' => false, 'message' => 'Invalid file type. Allowed: JPEG, PNG, GIF, WebP'];
    }
    
    if ($file['size'] > $maxSize) {
        return ['success' => false, 'message' => 'File too large. Maximum size is 5MB'];
    }
    
    return ['success' => true];
}

// Generate unique filename
function generateFilename($originalName, $prefix = 'project') {
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $timestamp = date('Ymd_His');
    $random = bin2hex(random_bytes(4));
    return "{$prefix}_{$timestamp}_{$random}.{$extension}";
}

// Only handle POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed. Use POST.']);
    exit;
}

// Check write permissions
if (!ensureUploadDirWritable()) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Upload directory is not writable.']);
    exit;
}

// Retrieve form data
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$category = $_POST['category'] ?? '';
$project_type = $_POST['project_type'] ?? 'image';
$video_url = $_POST['video_url'] ?? '';
$video_description = $_POST['video_description'] ?? '';
$tags = $_POST['tags'] ?? '';
$is_featured = isset($_POST['is_featured']) ? filter_var($_POST['is_featured'], FILTER_VALIDATE_BOOLEAN) : 0;
// status default is active
$status = $_POST['status'] ?? 'active';

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

// Handle Thumbnail Upload
if (!isset($_FILES['thumbnail']) || $_FILES['thumbnail']['error'] === UPLOAD_ERR_NO_FILE) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Thumbnail image is required.']);
    exit;
}

$validation = validateUpload($_FILES['thumbnail']);
if (!$validation['success']) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => $validation['message']]);
    exit;
}

// Save File
$filename = generateFilename($_FILES['thumbnail']['name'], 'project');
$destination = UPLOAD_DIR . $filename;

if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $destination)) {
    try {
        // Insert into DB
        $sql = "INSERT INTO portfolio_projects (
                    title, description, category, project_type, thumbnail, video_url, video_poster, tags, is_featured, status
                ) VALUES (
                    :title, :description, :category, :project_type, :thumbnail, :video_url, :video_poster, :tags, :is_featured, :status
                )";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':category' => $category,
            ':project_type' => $project_type,
            ':thumbnail' => $filename,
            ':video_url' => $video_url,
            ':video_poster' => $video_description,
            ':tags' => $tags,
            ':is_featured' => $is_featured ? 1 : 0,
            ':status' => $status
        ]);
        
        $newId = $pdo->lastInsertId();
        
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Project added successfully.',
            'data' => [
                'id' => $newId,
                'thumbnail_url' => './AdminApi/img/' . $filename
            ]
        ]);
        
    } catch (PDOException $e) {
        // Remove uploaded file if DB insert fails
        if (file_exists($destination)) {
            @unlink($destination);
        }
        
        log_event(['type' => 'db_error', 'message' => $e->getMessage()]);
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    log_event(['type' => 'upload_error', 'file' => 'thumbnail', 'dest' => $destination]);
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to save uploaded file.']);
}
