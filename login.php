<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
require 'db_connect.php';

// Initialize response array
$response = [
    'status' => 'error',
    'message' => 'An unexpected error occurred.'
];

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($username) || empty($password)) {
        $response['message'] = 'Username and password are required.';
        echo json_encode($response);
        exit;
    }

    try {
        // Query the database for the user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            // Login successful
            $response['status'] = 'success';
            $response['message'] = 'Login successful!';
        } else {
            // Login failed
            $response['message'] = 'Invalid username or password.';
        }
    } catch (PDOException $e) {
        // Handle database errors
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
}

echo json_encode($response);
exit;
?>
