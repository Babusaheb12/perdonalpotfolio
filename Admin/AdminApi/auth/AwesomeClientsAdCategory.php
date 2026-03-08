<?php
require_once '../../../databses/config.php';

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
    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data']);
        exit;
    }

    $name = trim($input['name'] ?? '');
    $description = trim($input['description'] ?? '');

    if (empty($name)) {
        echo json_encode(['success' => false, 'message' => 'Category name is required']);
        exit;
    }

    // Check if category already exists
    $checkStmt = $pdo->prepare("SELECT id FROM categories WHERE name = ?");
    $checkStmt->execute([$name]);
    
    if ($checkStmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Category already exists']);
        exit;
    }

    // Insert the new category
    $stmt = $pdo->prepare("INSERT INTO categories (name, description, status) VALUES (?, ?, 'active')");
    $result = $stmt->execute([$name, $description]);

    if ($result) {
        $categoryId = $pdo->lastInsertId();
        echo json_encode([
            'success' => true, 
            'message' => 'Category added successfully',
            'id' => $categoryId,
            'data' => [
                'id' => $categoryId,
                'name' => $name,
                'description' => $description,
                'status' => 'active'
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add category']);
    }

} catch (PDOException $e) {
    error_log("Database error in add category: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred']);
}
?>