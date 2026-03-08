<?php
require_once '../../../databses/config.php';

// Define upload directory
define('UPLOAD_DIR', __DIR__ . '/../img/');

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Only POST method is allowed']);
    exit;
}

try {
    // Debug: Log incoming request data
    error_log('AddAwesomeClients - Content-Type: ' . ($_SERVER['CONTENT_TYPE'] ?? 'unknown'));
    error_log('AddAwesomeClients - POST data: ' . print_r($_POST, true));
    error_log('AddAwesomeClients - FILES data: ' . print_r($_FILES, true));
    
    // Handle both JSON and FormData inputs
    $input = [];
    
    // Check if it's JSON data
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') !== false) {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'Invalid JSON input data']);
            exit;
        }
    } else {
        // Handle regular POST/form data
        $input = [
            'category' => $_POST['category'] ?? '',
            'client_name' => $_POST['client_name'] ?? '',
            'testimonial_text' => $_POST['testimonial_text'] ?? '',
            'rating' => $_POST['rating'] ?? null,
            'status' => $_POST['status'] ?? 'active'
        ];
    }

    $category = trim($input['category'] ?? '');
    $client_name = trim($input['client_name'] ?? '');
    $testimonial_text = trim($input['testimonial_text'] ?? '');
    $rating = $input['rating'] ?? null;
    $status = trim($input['status'] ?? 'active');
    
    // Validate required fields
    if (empty($category)) {
        echo json_encode(['success' => false, 'message' => 'Category is required']);
        exit;
    }
    
    if (empty($client_name)) {
        echo json_encode(['success' => false, 'message' => 'Client name is required']);
        exit;
    }

    // Validate status
    if (!in_array($status, ['active', 'inactive'])) {
        $status = 'active';
    }
    
    // Handle logo upload if provided
    $logo_path = null;
    
    // Debug: Log file upload information
    error_log('File upload debug - FILES array: ' . print_r($_FILES, true));
    error_log('Logo file isset: ' . (isset($_FILES['logo']) ? 'YES' : 'NO'));
    
    if (isset($_FILES['logo'])) {
        error_log('Logo file error code: ' . $_FILES['logo']['error']);
        error_log('Logo file name: ' . ($_FILES['logo']['name'] ?? 'N/A'));
        error_log('Logo file tmp_name: ' . ($_FILES['logo']['tmp_name'] ?? 'N/A'));
        error_log('Logo file size: ' . ($_FILES['logo']['size'] ?? 'N/A'));
    }
    
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        error_log('Processing logo upload...');
        
        // Create directory if it doesn't exist
        if (!is_dir(UPLOAD_DIR)) {
            error_log('Creating upload directory: ' . UPLOAD_DIR);
            if (!mkdir(UPLOAD_DIR, 0755, true)) {
                error_log('Failed to create upload directory');
                echo json_encode(['success' => false, 'message' => 'Failed to create upload directory']);
                exit;
            }
        }
        
        // Generate unique filename
        $fileExtension = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        error_log('File extension: ' . $fileExtension);
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.']);
            exit;
        }
        
        $fileName = uniqid() . '_' . basename($_FILES['logo']['name']);
        $filePath = UPLOAD_DIR . $fileName;
        
        error_log('Attempting to move file to: ' . $filePath);
        error_log('Source file exists: ' . (file_exists($_FILES['logo']['tmp_name']) ? 'YES' : 'NO'));
        
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $filePath)) {
            $logo_path = 'AdminApi/img/' . $fileName;
            error_log('File uploaded successfully. Logo path: ' . $logo_path);
        } else {
            $error = error_get_last();
            error_log('File upload failed. Last error: ' . print_r($error, true));
            echo json_encode(['success' => false, 'message' => 'Failed to upload logo file']);
            exit;
        }
    } else {
        // Check if logo_path was provided in input (for cases without file upload)
        $logo_path = $input['logo_path'] ?? null;
        error_log('No logo file uploaded. Logo path from input: ' . ($logo_path ?? 'NULL'));
    }

    // Validate rating if provided
    if ($rating !== null && $rating !== '') {
        $rating = floatval($rating);
        if ($rating < 0 || $rating > 5) {
            echo json_encode(['success' => false, 'message' => 'Rating must be between 0 and 5']);
            exit;
        }
    } else {
        $rating = null;
    }

    // Insert the new awesome client
    $stmt = $pdo->prepare("INSERT INTO awesome_clients (category, client_name, logo_path, testimonial_text, rating, status) VALUES (?, ?, ?, ?, ?, ?)");
    $result = $stmt->execute([$category, $client_name, $logo_path, $testimonial_text, $rating, $status]);

    if ($result) {
        $clientId = $pdo->lastInsertId();
        echo json_encode([
            'success' => true, 
            'message' => 'Awesome client added successfully',
            'id' => $clientId,
            'data' => [
                'id' => $clientId,
                'category' => $category,
                'client_name' => $client_name,
                'logo_path' => $logo_path,
                'testimonial_text' => $testimonial_text,
                'rating' => $rating,
                'status' => $status
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add awesome client']);
    }

} catch (PDOException $e) {
    error_log("Database error in add awesome client: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
} catch (Exception $e) {
    error_log("General error in add awesome client: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
}
?>