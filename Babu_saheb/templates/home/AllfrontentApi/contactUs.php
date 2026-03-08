<?php
// contactUs.php
// API endpoint to handle contact form submissions

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed. Use POST.'
    ]);
    exit;
}

require_once '../../../../databses/config.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// If JSON is invalid, try to get POST data
if (!is_array($input)) {
    $input = $_POST;
}

// Validate required fields
$name = isset($input['name']) ? trim($input['name']) : '';
$email = isset($input['email']) ? trim($input['email']) : '';
$message = isset($input['message']) ? trim($input['message']) : '';

// Optional fields
$phone_number = isset($input['phone_number']) ? trim($input['phone_number']) : null;
$subject = isset($input['subject']) ? trim($input['subject']) : null;

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email)) {
    $errors[] = 'Email is required';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}

// Check name length
if (strlen($name) > 100) {
    $errors[] = 'Name must not exceed 100 characters';
}

// Check email length
if (strlen($email) > 150) {
    $errors[] = 'Email must not exceed 150 characters';
}

// Check phone number length
if ($phone_number && strlen($phone_number) > 20) {
    $errors[] = 'Phone number must not exceed 20 characters';
}

// Check subject length
if ($subject && strlen($subject) > 200) {
    $errors[] = 'Subject must not exceed 200 characters';
}

// If there are validation errors, return them
if (!empty($errors)) {
    http_response_code(422);
    echo json_encode([
        'success' => false,
        'message' => 'Validation failed',
        'errors' => $errors
    ]);
    exit;
}

try {
    // Prepare SQL statement
    $sql = "INSERT INTO contact_messages (name, phone_number, email, subject, message, created_at) 
            VALUES (:name, :phone_number, :email, :subject, :message, NOW())";

    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':phone_number', $phone_number, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);

    // Execute the statement
    $stmt->execute();

    // Get the inserted ID
    $insertedId = $pdo->lastInsertId();

    // Success response
    http_response_code(201);
    echo json_encode([
        'success' => true,
        'message' => 'Thank you for contacting us! We will get back to you soon.',
        'data' => [
            'id' => (int) $insertedId,
            'name' => $name,
            'email' => $email
        ]
    ]);

} catch (PDOException $e) {
    // Log error for debugging (in production, log to file instead)
    error_log('Contact form error: ' . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to submit contact form. Please try again later.',
        'error' => $e->getMessage() // Remove in production
    ]);
}
?>