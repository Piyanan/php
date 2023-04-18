<?php
session_start();

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Get user data from database
if (!empty($_GET['user_id'])) {
  $user_id = $_GET['user_id'];
  $sql = "SELECT * FROM users WHERE user_id = ?";
  $stmt = mysqli_prepare($mysqli, $sql);
  $stmt->bind_param("i", $user_id);
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
      $sql = "UPDATE users SET username = ?, password = ? WHERE user_id = ?";
      if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ssi", $param_username, $param_password, $user_id);
        $param_username = trim($_POST["username"]);

        if (!empty($password)) {
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
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>
    <?php echo (empty($_GET['user_id'])) ? "Add user" : "Edit user"; ?>
  </title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
  <?php include("navbar.php"); ?>
  <h1 class="m-5 text-center">
    <?php echo (empty($_GET['user_id'])) ? "Add user" : "Edit user"; ?>
  </h1>
  <div class="container">
    <form method="post" action="">
      <div class="mb-3">
        <label class="form-label">Username:</label>
        <input type="text" class="form-control" name="username" value="<?php echo $username; ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Password:</label>
        <input type="password" class="form-control" name="password">
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm Password:</label>
        <input type="password" class="form-control" name="confirm_password">
      </div>
      <div class="mb-3 text-center">
        <button type="submit" name="submit" class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
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
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
</body>

</html>