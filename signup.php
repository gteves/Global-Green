<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
require 'db_connect.php';

$response = ["status" => "error", "message" => "Unknown error occurred."];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $username = htmlspecialchars(trim($_POST['username'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm-password'] ?? '');

    // Validate form data
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        $response["message"] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["message"] = "Invalid email format.";
    } elseif ($password !== $confirmPassword) {
        $response["message"] = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $response["message"] = "Password must be at least 8 characters long.";
    } else {
        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Insert data into the database
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (:username, :email, :password_hash)");
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password_hash' => $passwordHash,
            ]);
            $response["status"] = "success";
            $response["message"] = "Sign-up completed successfully!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { // Duplicate entry error code
                $response["message"] = "Username or email already exists.";
            } else {
                $response["message"] = "Database error: " . $e->getMessage();
            }
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
