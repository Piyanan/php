<?php
session_start();

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Get user data from database
if (isset($_GET['user_id'])) {
  $sql = "SELECT * FROM users WHERE user_id = ?";
  $stmt = mysqli_prepare($mysqli, $sql);
  $stmt->bind_param("i", $user_id);
  $user_id = $_GET['user_id'];
  $stmt->execute();
  $stmt->bind_result($user_id, $username, $hashed_password, $create_at);
  if (!$stmt->fetch()) {
    echo "Error fetching user data from database.";
  }
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Validate username
  if (empty(trim($_POST["username"]))) {
    $username_err = "Please enter a username.";
  } else {
    // Prepare a select statement
    $sql = "SELECT user_id FROM users WHERE username = ?";

    if ($stmt = $mysqli->prepare($sql)) {
      // Bind variables to the prepared statement as parameters
      $stmt->bind_param("s", $param_username);

      // Set parameters
      $param_username = trim($_POST["username"]);

      // Attempt to execute the prepared statement
      if ($stmt->execute()) {
        // Store result
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
          $username_err = "This username is already taken.";
        } else {
          $username = trim($_POST["username"]);
        }
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }

      // Close statement
      $stmt->close();
    }
  }

  // Validate password
  $password = '';
  if (!empty(trim($_POST["password"]))) {
    if (strlen(trim($_POST["password"])) < 6) {
      $password_err = "Password must have at least 6 characters.";
    } else {
      $password = trim($_POST["password"]);
    }
  }

  // Validate confirm password
  $confirm_password = '';
  if (!empty(trim($_POST["confirm_password"]))) {
    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($password_err) && ($password != $confirm_password)) {
      $confirm_password_err = "Password did not match.";
    }
  }
  
  // Check input errors before inserting in database
  if (empty($username_err) && empty($password_err) && empty($confirm_password_err)) {

    if (!empty($_GET['user_id'])) {
      // Update user information in database
      $sql = "UPDATE users SET name = ?, password = ? WHERE user_id = ?";

      if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ssi", $param_username, $param_password, $param_user_id);

        // Set parameters
        $param_username = $username;
        $param_user_id = $user_id;
        if (!empty($_POST["password"])) {
          $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
        } else {
          $param_password = $hashed_password;
        }
      }
    } else {
      // Prepare an insert statement
      $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

      if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ss", $param_username, $param_password);

        // Set parameters
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

      }
    }

    // Attempt to execute the prepared statement
    if ($stmt->execute()) {
      // Redirect to login page
      header("location: manage_users.php");
    } else {
      echo "Oops! Something went wrong. Please try again later.";
    }

    // Close statement
    $stmt->close();
  } else {
    echo "Error: Please correct the following errors and try again:";
    echo "<ul>";
    if ($username_err) {
      echo "<li>" . $username_err . "</li>";
    }
    if ($password_err) {
      echo "<li>" . $password_err . "</li>";
    }
    if ($confirm_password_err) {
      echo "<li>" . $confirm_password_err . "</li>";
    }
    echo "</ul>";
  }

  // Close connection
  $mysqli->close();
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Edit Page</title>
</head>

<body>
  <h2>Edit Page</h2>
  <form method="post" action="">
    <label>Username:</label>
    <input type="text" name="username" value="<?php echo $username; ?>"><br><br>
    <label>Password:</label>
    <input type="password" name="password"><br><br>
    <label>Confirm Password:</label>
    <input type="password" name="confirm_password"><br><br>
    <input type="submit" name="submit" value="Save">
  </form>
  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($username_err) && empty($password_err) && empty($confirm_password_err)) {
    echo "Error: Please correct the following errors and try again:";
    echo "<ul>";
    if ($username_err) {
      echo "<li>" . $username_err . "</li>";
    }
    if ($password_err) {
      echo "<li>" . $password_err . "</li>";
    }
    if ($confirm_password_err) {
      echo "<li>" . $confirm_password_err . "</li>";
    }
    echo "</ul>";
  }
  ?>
</body>

</html>