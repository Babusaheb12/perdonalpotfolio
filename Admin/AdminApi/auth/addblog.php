<?php
// Define upload directory
define('UPLOAD_DIR', __DIR__ . '/../img/');

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
    // Check if request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST method is allowed');
    }

    // Validate required fields
    $required_fields = ['title', 'category', 'author', 'readTime', 'content'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Field '$field' is required");
        }
    }

    // Extract and sanitize input data from POST
    $title = trim($_POST['title']);
    $subtitle = isset($_POST['subtitle']) ? trim($_POST['subtitle']) : null;
    $category = trim($_POST['category']);
    $author = trim($_POST['author']);
    $readTime = (int)$_POST['readTime'];
    $content = $_POST['content']; // HTML content from CKEditor
    $shortDesc = isset($_POST['shortDesc']) ? trim($_POST['shortDesc']) : null;
    $status = isset($_POST['status']) ? trim($_POST['status']) : 'active';
    
    // Generate slug from title if not provided
    $slug = isset($_POST['slug']) && !empty($_POST['slug']) ? trim($_POST['slug']) : generateSlug($title);

    // Handle file uploads
    $thumbnail = null;
    $banner_image = null;
    $video_url = null;

    // Handle thumbnail upload
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $thumbnail = uploadFile($_FILES['thumbnail'], 'thumbnails');
    }

    // Handle banner image upload
    if (isset($_FILES['banner']) && $_FILES['banner']['error'] === UPLOAD_ERR_OK) {
        $banner_image = uploadFile($_FILES['banner'], 'banners');
    }

    // Handle video upload
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $video_url = uploadFile($_FILES['video'], 'videos');
    }

    // Prepare SQL statement
    $sql = "INSERT INTO blogs (title, subtitle, slug, category, author, thumbnail, video_url, banner_image, short_description, full_description, read_time, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    
    // Execute the statement
    $result = $stmt->execute([
        $title,
        $subtitle,
        $slug,
        $category,
        $author,
        $thumbnail,
        $video_url,
        $banner_image,
        $shortDesc,
        $content, // HTML content from CKEditor
        $readTime . ' min read',
        $status
    ]);

    if ($result) {
        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Blog post created successfully',
            'data' => [
                'id' => $pdo->lastInsertId(),
                'title' => $title,
                'slug' => $slug
            ]
        ]);
    } else {
        throw new Exception('Failed to create blog post');
    }

} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

/**
 * Function to generate URL-friendly slug from title
 */
function generateSlug($title) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
    return $slug;
}

/**
 * Function to handle file uploads
 */
function uploadFile($file, $directory) {
    $targetDir = UPLOAD_DIR . $directory . "/";
    
    // Create directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $fileName = uniqid() . '_' . basename($file['name']);
    $targetFile = $targetDir . $fileName;
    
    // Validate file type
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi'];
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    if (!in_array($fileType, $allowedTypes)) {
        throw new Exception("Invalid file type: $fileType");
    }
    
    // Validate file size (max 5MB)
    // if ($file['size'] > 5000000) {
    //     throw new Exception("File size exceeds 5MB limit");
    // }

    // Validate file size (max 500MB)
$maxSize = 500 * 1024 * 1024; // 500MB

if ($file['size'] > $maxSize) {
    throw new Exception("Video size exceeds 500MB limit");
}

    
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return "AdminApi/img/" . $directory . "/" . $fileName;
    } else {
        throw new Exception("Failed to upload file");
    }
}
?>