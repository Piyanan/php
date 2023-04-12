<?php
session_start();

// Redirect user to login page if not logged in or not an admin
if (!isset($_SESSION["username"])) {
  header("Location: login_form.php");
  exit();
}

// Get list of all users from the database
require_once "config.php";
$sql = "SELECT * FROM users";
$result = mysqli_query($mysqli, $sql);
// Display list of users to admin
?>
<html>

<head>
  <title>Manage Users</title>
</head>

<body>
  <h1>Manage Users</h1>
  <table>
    <tr>
      <th>User ID</th>
      <th>Username</th>
      <th>Actions</th>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td>
          <?php echo $row["user_id"]; ?>
        </td>
        <td>
          <?php echo $row["username"]; ?>
        </td>
        <td>
          <a href="edit_user.php?user_id=<?php echo $row["user_id"]; ?>">Edit</a>
          <a href="delete_user.php?user_id=<?php echo $row["user_id"]; ?>">Delete</a>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>
  <a href="edit_user.php">Add User</a>
  <a href="logout.php">Logout</a>
</body>

</html>