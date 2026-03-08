<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../../../databses/config.php';

try {
    // Fetch all pricing packages
    $stmt = $pdo->prepare("SELECT * FROM pricing_packages ORDER BY created_at DESC");
    $stmt->execute();
    $pricingPackages = $stmt->fetchAll();

    // Prepare response data
    $response = [
        'pricing_packages' => $pricingPackages,
        'success' => true,
        'message' => 'Pricing packages fetched successfully'
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