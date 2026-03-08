<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../../../databses/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $package_name = $data['package_name'] ?? '';
    $title = $data['title'] ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $price = $data['price'] ?? '';
    $description = $data['description'] ?? '';
    $features = $data['features'] ?? '';
    $is_recommended = isset($data['is_recommended']) ? ($data['is_recommended'] ? 1 : 0) : 0;

    if (empty($package_name) || empty($title)) {
        echo json_encode(['success' => false, 'message' => 'Package name and title are required']);
        exit;
    }

    try {
        // Check if package already exists
        $stmt = $pdo->prepare("SELECT id FROM pricing_packages WHERE package_name = ?");
        $stmt->execute([$package_name]);
        $existingPackage = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingPackage) {
            // Update existing package
            $stmt = $pdo->prepare("UPDATE pricing_packages SET title = ?, subtitle = ?, price = ?, description = ?, features = ?, is_recommended = ?, updated_at = CURRENT_TIMESTAMP WHERE package_name = ?");
            $result = $stmt->execute([$title, $subtitle, $price, $description, $features, $is_recommended, $package_name]);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Package updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update package']);
            }
        } else {
            // Insert new package
            $stmt = $pdo->prepare("INSERT INTO pricing_packages (package_name, title, subtitle, price, description, features, is_recommended) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$package_name, $title, $subtitle, $price, $description, $features, $is_recommended]);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Package added successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add package']);
            }
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>