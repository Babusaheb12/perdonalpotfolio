<?php
// --- Configuration ---
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'deepakPotfolio'); // your database name
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPP default is empty password

// --- PDO connection (recommended) ---
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
$pdoOptions = [
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES => false,
];

try {
	$pdo = new PDO($dsn, DB_USER, DB_PASS, $pdoOptions);
} catch (PDOException $e) {
	// In production, log $e->getMessage() and show a generic message instead
	die('Database connection failed: ' . $e->getMessage());
}

// --- mysqli connection (optional) ---
// Uncomment if you need a mysqli object instead of PDO.
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($mysqli->connect_error) {
	// Comment out the next line if you prefer to continue when mysqli isn't used.
	// die('MySQLi connect error: ' . $mysqli->connect_error);
}
?>
