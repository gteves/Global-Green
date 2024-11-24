<?php
// Database configuration
$host = 'localhost';       // Hostname (use 'localhost' for local MySQL server)
$dbname = 'globalgreen';   // Your database name
$user = 'root';            // Your MySQL username
$pass = 'Breathe';         // Your MySQL password (leave empty if no password)

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);

    // Set error mode to exceptions for debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Display success message for testing (comment out in production)
    // echo "Database connection successful!";
} catch (PDOException $e) {
    // Log the error and show a user-friendly message
    error_log("Database connection error: " . $e->getMessage(), 3, 'error_log.log');
    die("Could not connect to the database. Please try again later.");
}
?>
