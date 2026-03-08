<?php
// Start session if not already started
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
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
    
    // Determine the record type
    $recordType = $input['type'] ?? '';
    
    // Validate record type
    if (!in_array($recordType, ['experience', 'education', 'skill', 'achievement'])) {
        throw new Exception('Invalid record type. Must be experience, education, skill, or achievement');
    }
    
    // Prepare data based on record type
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
            
            $stmt = $pdo->prepare("INSERT INTO experiences (job_title, company_name, duration, rating, description) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$job_title, $company_name, $duration, $rating, $description]);
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
            
            $stmt = $pdo->prepare("INSERT INTO educations (degree_course, institution, duration, grade_score, description) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$degree_course, $institution, $duration, $grade_score, $description]);
            break;
            
        case 'skill':
            $skill_name = $input['skill_name'] ?? '';
            $rating = $input['rating'] ?? '';
            
            if (empty($skill_name) || empty($rating)) {
                throw new Exception('Skill name and rating are required for skill');
            }
            
            $stmt = $pdo->prepare("INSERT INTO skills (skill_name, rating) VALUES (?, ?)");
            $result = $stmt->execute([$skill_name, $rating]);
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
            
            $stmt = $pdo->prepare("INSERT INTO achievements (title, organization, date_achieved, rating_score, description) VALUES (?, ?, ?, ?, ?)");
            $result = $stmt->execute([$title, $organization, $date_achieved, $rating_score, $description]);
            break;
            
        default:
            throw new Exception('Invalid record type');
    }
    
    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => ucfirst($recordType) . ' added successfully',
            'id' => $pdo->lastInsertId()
        ]);
    } else {
        throw new Exception('Failed to add ' . $recordType);
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
