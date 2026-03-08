<?php
// Start session if not already started
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || !isset($_SESSION['admin_email'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Include database configuration
require_once '../../../databses/config.php';

// Set content type to JSON
header('Content-Type: application/json');

try {
    // Fetch all experiences
    $stmt = $pdo->prepare("SELECT id, job_title, company_name, duration, rating, description, created_at FROM experiences ORDER BY created_at DESC");
    $stmt->execute();
    $experiences = $stmt->fetchAll();

    // Fetch all education records
    $stmt = $pdo->prepare("SELECT id, degree_course, institution, duration, grade_score, description, created_at FROM educations ORDER BY created_at DESC");
    $stmt->execute();
    $educations = $stmt->fetchAll();

    // Fetch all skills
    $stmt = $pdo->prepare("SELECT id, skill_name, rating, created_at FROM skills ORDER BY created_at DESC");
    $stmt->execute();
    $skills = $stmt->fetchAll();

    // Fetch all achievements
    $stmt = $pdo->prepare("SELECT id, title, organization, date_achieved, rating_score, description, created_at FROM achievements ORDER BY created_at DESC");
    $stmt->execute();
    $achievements = $stmt->fetchAll();

    // Prepare response data
    $response = [
        'experiences' => $experiences,
        'educations' => $educations,
        'skills' => $skills,
        'achievements' => $achievements,
        'success' => true
    ];

    // Return JSON response
    echo json_encode($response);

} catch (PDOException $e) {
    // Log the error and return a generic message
    error_log('Database error in fetchResume.php: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error occurred']);
}
?>