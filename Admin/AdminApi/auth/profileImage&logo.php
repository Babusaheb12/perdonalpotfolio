<?php
// profileImage&logo.php
// API endpoint to upload and manage profile images and logos

header('Content-Type: application/json; charset=utf-8');
// Allow CORS for local testing (adjust in production)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Load DB connection (PDO: $pdo)
require_once __DIR__ . '/../../../databses/config.php';

// Define upload directory
define('UPLOAD_DIR', __DIR__ . '/../img/');
// Ensure upload directory exists
if (!is_dir(UPLOAD_DIR)) {
    @mkdir(UPLOAD_DIR, 0777, true);
}
// Ensure upload directory is writable
if (!is_writable(UPLOAD_DIR)) {
    @chmod(UPLOAD_DIR, 0777);
}

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
 * Validate uploaded file
 */
function validateUpload($file, $type) {
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

/**
 * Get human-readable upload error message
 */
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

/**
 * Generate unique filename
 */
function generateFilename($originalName, $type) {
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $timestamp = date('Ymd_His');
    $random = bin2hex(random_bytes(4));
    return "{$type}_{$timestamp}_{$random}.{$extension}";
}

/**
 * Handle POST request - Upload images
 */
function handleUpload($pdo) {
    // Ensure table exists
    ensureTableExists($pdo);
    if (!ensureUploadDirWritable()) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Upload directory is not writable: ' . UPLOAD_DIR]);
        exit;
    }
    
    $result = [
        'profile' => null,
        'logo' => null,
        'errors' => []
    ];
    
    // Handle profile image upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $validation = validateUpload($_FILES['profile_image'], 'profile');
        if ($validation['success']) {
            $filename = generateFilename($_FILES['profile_image']['name'], 'profile');
            $destination = UPLOAD_DIR . $filename;
            
            if (is_uploaded_file($_FILES['profile_image']['tmp_name']) && move_uploaded_file($_FILES['profile_image']['tmp_name'], $destination)) {
                $stmtOld = $pdo->prepare("SELECT image_path FROM profile_images WHERE image_path LIKE 'profile_%'");
                $stmtOld->execute();
                $rowsOld = $stmtOld->fetchAll();
                foreach ($rowsOld as $r) {
                    $oldPath = UPLOAD_DIR . $r['image_path'];
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
                $pdo->prepare("DELETE FROM profile_images WHERE image_path LIKE 'profile_%'")->execute();
                $stmt = $pdo->prepare("INSERT INTO profile_images (image_path, status) VALUES (?, 'active')");
                $stmt->execute([$filename]);
                
                $result['profile'] = [
                    'id' => $pdo->lastInsertId(),
                    'filename' => $filename,
                    'url' => './AdminApi/img/' . $filename
                ];
            } else {
                $result['errors'][] = 'Failed to save profile image';
                log_event(['type' => 'upload_error', 'file' => 'profile', 'dest' => $destination, 'writable' => is_writable(UPLOAD_DIR)]);
            }
        } else {
            $result['errors'][] = 'Profile image: ' . $validation['message'];
        }
    }
    
    // Handle logo image upload
    if (isset($_FILES['logo_image']) && $_FILES['logo_image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $validation = validateUpload($_FILES['logo_image'], 'logo');
        if ($validation['success']) {
            $filename = generateFilename($_FILES['logo_image']['name'], 'logo');
            $destination = UPLOAD_DIR . $filename;
            
            if (is_uploaded_file($_FILES['logo_image']['tmp_name']) && move_uploaded_file($_FILES['logo_image']['tmp_name'], $destination)) {
                $stmtOld = $pdo->prepare("SELECT image_path FROM profile_images WHERE image_path LIKE 'logo_%'");
                $stmtOld->execute();
                $rowsOld = $stmtOld->fetchAll();
                foreach ($rowsOld as $r) {
                    $oldPath = UPLOAD_DIR . $r['image_path'];
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
                $pdo->prepare("DELETE FROM profile_images WHERE image_path LIKE 'logo_%'")->execute();
                $stmt = $pdo->prepare("INSERT INTO profile_images (image_path, status) VALUES (?, 'active')");
                $stmt->execute([$filename]);
                
                $result['logo'] = [
                    'id' => $pdo->lastInsertId(),
                    'filename' => $filename,
                    'url' => './AdminApi/img/' . $filename
                ];
            } else {
                $result['errors'][] = 'Failed to save logo image';
                log_event(['type' => 'upload_error', 'file' => 'logo', 'dest' => $destination, 'writable' => is_writable(UPLOAD_DIR)]);
            }
        } else {
            $result['errors'][] = 'Logo image: ' . $validation['message'];
        }
    }
    
    if (empty($result['profile']) && empty($result['logo'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No valid files uploaded. ' . implode('; ', $result['errors'])]);
        exit;
    }
    
    log_event([
        'type' => 'upload',
        'result' => $result
    ]);
    
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Images uploaded successfully',
        'data' => $result
    ]);
    exit;
}

/**
 * Handle GET request - Fetch active images
 */
function handleFetch($pdo) {
    // Ensure table exists
    ensureTableExists($pdo);
    
    $type = isset($_GET['type']) ? $_GET['type'] : null;
    
    try {
        if ($type && in_array($type, ['profile', 'logo'])) {
            $stmt = $pdo->prepare("SELECT id, image_path, status, created_at, updated_at FROM profile_images WHERE image_path LIKE ? AND status = 'active' ORDER BY created_at DESC LIMIT 1");
            $stmt->execute([$type . '_%']);
            $row = $stmt->fetch();
            
            if (!$row) {
                echo json_encode(['success' => true, 'data' => null, 'message' => 'No active ' . $type . ' image found']);
                exit;
            }
            
            $row['url'] = './AdminApi/img/' . $row['image_path'];
            echo json_encode(['success' => true, 'data' => $row]);
            exit;
        } else {
            $result = ['profile' => null, 'logo' => null];
            $stmtP = $pdo->prepare("SELECT id, image_path, status, created_at, updated_at FROM profile_images WHERE image_path LIKE 'profile_%' AND status = 'active' ORDER BY created_at DESC LIMIT 1");
            $stmtP->execute();
            $rowP = $stmtP->fetch();
            if ($rowP) {
                $rowP['url'] = './AdminApi/img/' . $rowP['image_path'];
                $result['profile'] = $rowP;
            }
            $stmtL = $pdo->prepare("SELECT id, image_path, status, created_at, updated_at FROM profile_images WHERE image_path LIKE 'logo_%' AND status = 'active' ORDER BY created_at DESC LIMIT 1");
            $stmtL->execute();
            $rowL = $stmtL->fetch();
            if ($rowL) {
                $rowL['url'] = './AdminApi/img/' . $rowL['image_path'];
                $result['logo'] = $rowL;
            }
            
            echo json_encode(['success' => true, 'data' => $result]);
            exit;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        exit;
    }
}

/**
 * Handle DELETE request - Remove image
 */
function handleDelete($pdo) {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $id = isset($input['id']) ? (int)$input['id'] : 0;
    $type = isset($input['type']) ? $input['type'] : null;
    
    if (!$id && !$type) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'id or type is required']);
        exit;
    }
    
    try {
        if ($id) {
            $stmt = $pdo->prepare("SELECT image_path FROM profile_images WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            
            if (!$row) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Image not found']);
                exit;
            }
            
            $filePath = UPLOAD_DIR . $row['image_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Delete record
            $pdo->prepare("DELETE FROM profile_images WHERE id = ?")->execute([$id]);
            
            log_event(['type' => 'delete', 'id' => $id, 'file' => $row['image_path']]);
            
            echo json_encode(['success' => true, 'message' => 'Image deleted successfully']);
            exit;
        } else {
            $stmt = $pdo->prepare("SELECT image_path FROM profile_images WHERE image_path LIKE ?");
            $stmt->execute([$type . '_%']);
            $rows = $stmt->fetchAll();
            
            foreach ($rows as $row) {
                $filePath = UPLOAD_DIR . $row['image_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $pdo->prepare("DELETE FROM profile_images WHERE image_path LIKE ?")->execute([$type . '_%']);
            
            log_event(['type' => 'delete_all', 'category' => $type]);
            
            echo json_encode(['success' => true, 'message' => 'All ' . $type . ' images deleted']);
            exit;
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
        exit;
    }
}

// Route request based on method
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        handleUpload($pdo);
        break;
    case 'GET':
        handleFetch($pdo);
        break;
    case 'DELETE':
        handleDelete($pdo);
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed. Use POST, GET, or DELETE.']);
        exit;
}
?>
