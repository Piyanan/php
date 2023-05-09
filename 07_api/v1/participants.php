<?php
require_once __DIR__ . "/../vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Define secret key for JWT
define('SECRET_KEY', 'your-secret-key');

// Define function to authenticate user using JWT
function authenticateUser($jwt)
{
  try {
    $decoded = JWT::decode($jwt, new Key(SECRET_KEY, 'HS256'));
    // Check if JWT has expired
    if (time() > $decoded->exp) {
      return null;
    }

    // Return user ID and username
    return array('user_id' => $decoded->user_id, 'username' => $decoded->username);
  } catch (Exception $e) {
    return null;
  }
}

// Get JWT token from request headers
if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
  http_response_code(401); // Unauthorized
  echo json_encode(array('message' => 'Authentication failed'));
  exit();
}
$token = $matches[1];

if (empty($token)) {
  http_response_code(401); // Unauthorized
  echo json_encode(array('message' => 'Authentication failed'));
  exit();
}

// Authenticate user using JWT token
$user = authenticateUser($token);
if (empty($user)) {
  http_response_code(401); // Unauthorized
  echo json_encode(array('message' => 'Authentication failed'));
  exit();
}

// Set up database connection
$dbHost = "localhost";
$dbName = "databasename";
$dbUser = "username";
$dbPass = "password";
$db = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass);

// Set up the HTTP response headers
header('Content-Type: application/json');

// Check the HTTP method
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
  case 'GET':
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    getParticipants($db, $id);
    break;
  case 'POST':
    $data = json_decode(file_get_contents('php://input'), true);
    addParticipant($db, $data);
    break;
  case 'PUT':
    if (empty($_GET['id'])) {
      http_response_code(400); // Bad request
      echo json_encode(array('message' => 'Missing participant ID'));
      exit();
    }
    $id = $_GET['id'];
    $data = json_decode(file_get_contents('php://input'), true);
    updateParticipant($db, $id, $data);
    break;
  case 'DELETE':
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    deleteParticipant($db, $id);
    break;
  default:
    http_response_code(405); // Method not allowed
    echo json_encode(array('message' => 'Method not allowed'));
}

function getParticipants($db, $id = null)
{
  // Build the SQL query
  $sql = 'SELECT * FROM participants';
  if (isset($id)) {
    $sql .= ' WHERE participant_id = :id';
  }
  $stmt = $db->prepare($sql);
  if (isset($id)) {
    $stmt->bindParam(':id', $id);
  }
  // Execute the query
  $stmt->execute();
  // Fetch the results
  $participants = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // Return the results as JSON
  echo json_encode($participants);
}

function addParticipant($db, $data)
{
  // Build the SQL query
  $sql = 'INSERT INTO participants (name, email, phone, address, type) VALUES (:name, :email, :phone, :address, :type)';
  $stmt = $db->prepare($sql);
  // Bind the parameters with their types and lengths
  $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR, 255);
  $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR, 255);
  $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR, 20);
  $stmt->bindParam(':address', $data['address'], PDO::PARAM_STR);
  $stmt->bindParam(':type', $data['type'], PDO::PARAM_STR, 10);
  // Execute the query
  if ($stmt->execute()) {
    // Return the new participant ID as JSON
    $id = $db->lastInsertId();
    echo json_encode(array('participant_id' => $id));
  } else {
    http_response_code(500); // Internal server error
    echo json_encode(array('message' => 'Failed to add participant'));
  }
}

function updateParticipant($db, $id, $data)
{
  // Build the SQL query
  $sql = 'UPDATE participants SET name = :name, email = :email, phone = :phone, address = :address, type = :type WHERE participant_id = :id';
  $stmt = $db->prepare($sql);
  // Bind the parameters
  $stmt->bindParam(':id', $id);
  // Bind the parameters with their types and lengths
  $stmt->bindParam(':name', $data['name'], PDO::PARAM_STR, 255);
  $stmt->bindParam(':email', $data['email'], PDO::PARAM_STR, 255);
  $stmt->bindParam(':phone', $data['phone'], PDO::PARAM_STR, 20);
  $stmt->bindParam(':address', $data['address'], PDO::PARAM_STR);
  $stmt->bindParam(':type', $data['type'], PDO::PARAM_STR, 10);
  // Execute the query
  if ($stmt->execute()) {
    // Return success message as JSON
    echo json_encode(array('message' => 'Participant updated successfully'));
  } else {
    http_response_code(500); // Internal server error
    echo json_encode(array('message' => 'Failed to update participant'));
  }
}

function deleteParticipant($db, $id)
{
  // Build the SQL query
  $sql = 'DELETE FROM participants WHERE participant_id = :id';
  $stmt = $db->prepare($sql);
  // Bind the parameters
  $stmt->bindParam(':id', $id);
  // Execute the query
  if ($stmt->execute()) {
    // Return success message as JSON
    echo json_encode(array('message' => 'Participant deleted successfully'));
  } else {
    http_response_code(500); // Internal server error
    echo json_encode(array('message' => 'Failed to delete participant'));
  }
}