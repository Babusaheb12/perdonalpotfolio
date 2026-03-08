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
    
    // Prepare update query based on record type
    $tableMap = [
        'experience' => 'experiences',
        'education' => 'educations', 
        'skill' => 'skills',
        'achievement' => 'achievements'
    ];
    
    $tableName = $tableMap[$recordType];
    $params = [];
    $setClause = '';
    
    switch ($recordType) {
        case 'experience':
            $job_title = $input['job_title'] ?? '';
            $company_name = $input['company_name'] ?? '';
            $duration = $input['duration'] ?? '';
            $rating = $input['rating'] ?? '';
            $description = $input['description'] ?? '';
            
            if (empty($job_title) || empty($company_name) || empty($duration) || empty($rating)) {
                throw new Exception('Job title, company name, duration, and rating are required for experience');
            }
            
            $setClause = "job_title = ?, company_name = ?, duration = ?, rating = ?, description = ?";
            $params = [$job_title, $company_name, $duration, $rating, $description];
            break;
            
        case 'education':
            $degree_course = $input['degree_course'] ?? '';
            $institution = $input['institution'] ?? '';
            $duration = $input['duration'] ?? '';
            $grade_score = $input['grade_score'] ?? '';
            $description = $input['description'] ?? '';
            
            if (empty($degree_course) || empty($institution) || empty($duration)) {
                throw new Exception('Degree/Course, institution, and duration are required for education');
            }
            
            $setClause = "degree_course = ?, institution = ?, duration = ?, grade_score = ?, description = ?";
            $params = [$degree_course, $institution, $duration, $grade_score, $description];
            break;
            
        case 'skill':
            $skill_name = $input['skill_name'] ?? '';
            $rating = $input['rating'] ?? '';
            
            if (empty($skill_name) || empty($rating)) {
                throw new Exception('Skill name and rating are required for skill');
            }
            
            $setClause = "skill_name = ?, rating = ?";
            $params = [$skill_name, $rating];
            break;
            
        case 'achievement':
            $title = $input['title'] ?? '';
            $organization = $input['organization'] ?? '';
            $date_achieved = $input['date_achieved'] ?? '';
            $rating_score = $input['rating_score'] ?? '';
            $description = $input['description'] ?? '';
            
            if (empty($title) || empty($organization) || empty($date_achieved)) {
                throw new Exception('Title, organization, and date are required for achievement');
            }
            
            $setClause = "title = ?, organization = ?, date_achieved = ?, rating_score = ?, description = ?";
            $params = [$title, $organization, $date_achieved, $rating_score, $description];
            break;
    }
    
    // Add the ID to the parameters for the WHERE clause
    $params[] = $recordId;
    
    $stmt = $pdo->prepare("UPDATE {$tableName} SET {$setClause} WHERE id = ?");
    $result = $stmt->execute($params);
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => ucfirst($recordType) . ' updated successfully'
        ]);
    } else {
        throw new Exception('Failed to update ' . $recordType);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>