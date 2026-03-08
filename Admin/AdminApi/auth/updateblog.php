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
require_once '../../../../../databses/config.php';

// Set content type to JSON
header('Content-Type: application/json');

try {
    // Check if request method is POST or PUT
    if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT'])) {
        throw new Exception('Only POST or PUT method is allowed');
    }

    // Validate required fields
    $required_fields = ['id', 'title', 'category', 'author', 'readTime', 'content'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Field '$field' is required");
        }
    }

    // Extract and sanitize input data from POST
    $id = (int)$_POST['id'];
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

    // Prepare SQL statement based on whether files are being uploaded
    if ($thumbnail || $banner_image || $video_url) {
        // Update with file paths
        $sql = "UPDATE blogs SET title = ?, subtitle = ?, slug = ?, category = ?, author = ?, short_description = ?, full_description = ?, read_time = ?, status = ?";
        $params = [$title, $subtitle, $slug, $category, $author, $shortDesc, $content, $readTime . ' min read', $status];
        
        if ($thumbnail) {
            $sql .= ", thumbnail = ?";
            $params[] = $thumbnail;
        }
        
        if ($banner_image) {
            $sql .= ", banner_image = ?";
            $params[] = $banner_image;
        }
        
        if ($video_url) {
            $sql .= ", video_url = ?";
            $params[] = $video_url;
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
    } else {
        // Update without file paths
        $sql = "UPDATE blogs SET title = ?, subtitle = ?, slug = ?, category = ?, author = ?, short_description = ?, full_description = ?, read_time = ?, status = ? WHERE id = ?";
        $params = [$title, $subtitle, $slug, $category, $author, $shortDesc, $content, $readTime . ' min read', $status, $id];
    }
    
    $stmt = $pdo->prepare($sql);
    
    // Execute the statement
    $result = $stmt->execute($params);

    if ($result && $stmt->rowCount() > 0) {
        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Blog post updated successfully',
            'data' => [
                'id' => $id,
                'title' => $title,
                'slug' => $slug
            ]
        ]);
    } else {
        throw new Exception('Failed to update blog post or blog post not found');
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