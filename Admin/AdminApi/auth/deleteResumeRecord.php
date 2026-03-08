<?php
// Start session if not already started
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Include database configuration
require_once '../../../databses/config.php';

header('Content-Type: application/json');

try {
    // Get the JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Invalid JSON input');
    }
    
    // Determine the record type and ID
    $recordType = $input['type'] ?? '';
    $recordId = $input['id'] ?? '';
    
    if (empty($recordType) || empty($recordId)) {
        throw new Exception('Record type and ID are required');
    }
    
    // Validate record type
    if (!in_array($recordType, ['experience', 'education', 'skill', 'achievement'])) {
        throw new Exception('Invalid record type. Must be experience, education, skill, or achievement');
    }
    
    // Prepare delete query based on record type
    $tableMap = [
        'experience' => 'experiences',
        'education' => 'educations', 
        'skill' => 'skills',
        'achievement' => 'achievements'
    ];
    
    $tableName = $tableMap[$recordType];
    $stmt = $pdo->prepare("DELETE FROM {$tableName} WHERE id = ?");
    $result = $stmt->execute([$recordId]);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => ucfirst($recordType) . ' deleted successfully'
        ]);
    } else {
        throw new Exception('Failed to delete ' . $recordType);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>