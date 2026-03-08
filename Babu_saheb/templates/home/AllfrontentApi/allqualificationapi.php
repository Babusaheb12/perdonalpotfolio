<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');


require_once '../../../../databses/config.php';

try {
    // Fetch all educations
    $stmtEducations = $pdo->prepare("SELECT * FROM educations ORDER BY created_at DESC");
    $stmtEducations->execute();
    $educations = $stmtEducations->fetchAll();

    // Fetch all experiences
    $stmtExperiences = $pdo->prepare("SELECT * FROM experiences ORDER BY created_at DESC");
    $stmtExperiences->execute();
    $experiences = $stmtExperiences->fetchAll();

    // Fetch all skills
    $stmtSkills = $pdo->prepare("SELECT * FROM skills ORDER BY created_at DESC");
    $stmtSkills->execute();
    $skills = $stmtSkills->fetchAll();

    // Fetch all achievements
    $stmtAchievements = $pdo->prepare("SELECT * FROM achievements ORDER BY created_at DESC");
    $stmtAchievements->execute();
    $achievements = $stmtAchievements->fetchAll();

    // Prepare response data
    $response = [
        'educations' => $educations,
        'experiences' => $experiences,
        'skills' => $skills,
        'achievements' => $achievements,
        'success' => true,
        'message' => 'Data fetched successfully'
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>