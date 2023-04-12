<?php
// Get user ID from URL
$user_id = $_GET["user_id"];

// Delete user from database
require_once "config.php";
$sql = "DELETE FROM users WHERE user_id = $user_id";
$result = mysqli_query($mysqli, $sql);

// Check if user was successfully deleted from the database
if ($result) {
  // Redirect user to manage_users.php page
  header("Location: manage_users.php");
  exit();
} else {
  $error = "Error deleting user from database.";
  // Display error message to user
  header("Location: manage_users.php?error=" . urlencode($error));
  exit();
}