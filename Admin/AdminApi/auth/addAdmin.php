<?php
// addAdmin.php
// API endpoint to add an admin user into the `admins` table.

header('Content-Type: application/json; charset=utf-8');
// Allow CORS for local testing (adjust in production)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	// CORS preflight
	http_response_code(204);
	exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	http_response_code(405);
	echo json_encode(['success' => false, 'message' => 'Method not allowed. Use POST.']);
	exit;
}

// Load DB connection (PDO: $pdo, optional $mysqli)
require_once __DIR__ . '/../../../databses/config.php';

// Read JSON body
$input = json_decode(file_get_contents('php://input'), true);
// --- Logging helper (logs to databses/api.log) ---
function api_log_file()
{
	// log file placed in databses directory: adjust if you prefer another location
	return __DIR__ . '/../../../databses/api.log';
}

function redact_sensitive($data)
{
	if (!is_array($data)) return $data;
	$copy = $data;
	if (isset($copy['password'])) $copy['password'] = '***REDACTED***';
	return $copy;
}

function log_event(array $event)
{
	$event['ts'] = date('c');
	$line = json_encode($event, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	@file_put_contents(api_log_file(), $line . PHP_EOL, FILE_APPEND | LOCK_EX);
}

// Log incoming request (mask password)
log_event([
	'type' => 'request',
	'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
	'path' => $_SERVER['REQUEST_URI'] ?? '',
	'body' => redact_sensitive($input)
]);

if (!is_array($input)) {
	http_response_code(400);
	echo json_encode(['success' => false, 'message' => 'Invalid JSON body']);
	exit;
}

$email = isset($input['email']) ? trim($input['email']) : '';
$password = isset($input['password']) ? $input['password'] : '';

// Basic validation
if (empty($email) || empty($password)) {
	http_response_code(422);
	echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
	exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	http_response_code(422);
	echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
	exit;
}

if (strlen($password) < 6) {
	http_response_code(422);
	echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
	exit;
}

try {
	// Check for duplicate email
	$stmt = $pdo->prepare('SELECT id FROM admins WHERE email = :email LIMIT 1');
	$stmt->execute([':email' => $email]);
	if ($stmt->fetch()) {
		http_response_code(409);
		echo json_encode(['success' => false, 'message' => 'Email already registered.']);
		exit;
	}

	// Hash password
	$hash = password_hash($password, PASSWORD_DEFAULT);

	// Insert admin
	$insert = $pdo->prepare('INSERT INTO admins (email, password, status) VALUES (:email, :password, :status)');
	$insert->execute([
		':email' => $email,
		':password' => $hash,
		':status' => 'active'
	]);

	$newId = $pdo->lastInsertId();

	$response = ['success' => true, 'message' => 'Admin created', 'id' => $newId];
	http_response_code(201);
	echo json_encode($response);

	// Log response
	log_event([
		'type' => 'response',
		'status' => 201,
		'body' => $response
	]);
	exit;

} catch (Exception $e) {
	// In production, log the exception and return a generic message
	$resp = ['success' => false, 'message' => 'Server error: ' . $e->getMessage()];
	http_response_code(500);
	echo json_encode($resp);
	// Log error response with exception message (you may strip message in production)
	log_event([
		'type' => 'response',
		'status' => 500,
		'body' => $resp,
		'error' => $e->getMessage()
	]);
	exit;
}

?>
