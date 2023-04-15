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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manage Users</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
  <h1 class="mb-3 text-center">Manage Users</h1>
  <div class="container">

    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td>
                <?php echo $row["user_id"]; ?>
              </td>
              <td>
                <?php echo $row["username"]; ?>
              </td>
              <td>
                <a href="edit_user.php?user_id=<?php echo $row["user_id"]; ?>"
                  class="btn btn-primary btn-sm me-2">Edit</a>
                <a href="delete_user.php?user_id=<?php echo $row["user_id"]; ?>" class="btn btn-danger btn-sm">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div class="my-3">
      <a href="edit_user.php" class="btn btn-success">Add User</a>
      <a href="logout.php" class="btn btn-secondary">Logout</a>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
</body>

</html>