<?php
session_start();

// Redirect user to login page if not logged in
if (!isset($_SESSION["username"])) {
    header("Location: login_form.php");
    exit();
}

// Display welcome message to logged-in user
echo "Welcome, " . $_SESSION["username"] . "! You are now logged in.";
?>
