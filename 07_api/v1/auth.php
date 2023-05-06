<?php
require_once __DIR__ . "/../vendor/autoload.php";

use Firebase\JWT\JWT;

// Define secret key for JWT
define('SECRET_KEY', 'your-secret-key');

// Define function to generate JWT
function generateJWT($user_id, $username)
{
    $payload = array(
        'user_id' => $user_id,
        'username' => $username,
        'iat' => time(),
        // Issued at
        'exp' => time() + (60 * 60 * 24) // Expiration time (1 day)
    );

    $jwt = JWT::encode($payload, SECRET_KEY, 'HS256');
    return $jwt;
}

// Set up the HTTP response headers
header('Content-Type: application/json');

// Define API endpoint for user authentication
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get username and password from request body
    $data = json_decode(file_get_contents('php://input'));
    $username = $data->username;
    $password = $data->password;

    // Set up database connection
    $dbHost = "localhost";
    $dbName = "databasename";
    $dbUser = "username";
    $dbPass = "password";
    $db = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);

    // Prepare and execute query to fetch user with matching username
    $query = "SELECT user_id, password FROM users WHERE username=:username";
    $stmt = $db->prepare($query);
    $stmt->execute(array(':username' => $username));

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch();

        // Verify password
        if (password_verify($password, $row['password'])) {
            // Generate JWT and return it in response body
            $jwt = generateJWT($row['user_id'], $username);
            echo json_encode(array('jwt' => $jwt));
        } else {
            // Return error message in response body
            echo json_encode(array('error' => 'Authentication failed'));
        }
    } else {
        // Return error message in response body
        echo json_encode(array('error' => 'Authentication failed'));
    }

    // Close database connection
    $db = null;
} else {
    // Handle unauthorized access
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
}