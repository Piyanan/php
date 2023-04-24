<?php
// Database configuration
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'database_name');

// Attempt to connect to MySQL database
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($mysqli->connect_error) {
  die("ERROR: Could not connect. " . $mysqli->connect_error);
}

const BASE_URL = 'https://10.9.42.233/yo';
?>