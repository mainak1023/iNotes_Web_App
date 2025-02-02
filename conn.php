<?php
// Database configuration
$host = 'localhost';
$dbname = 'practice';  // Your database name
$username = 'root';    // Your database username
$password = '';        // Your database password

try {
    // Create PDO connection
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Set PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Set default fetch mode to associative array
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    // If connection fails, return error in JSON format
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(['error' => "Connection failed: " . $e->getMessage()]);
    exit;
}
?>