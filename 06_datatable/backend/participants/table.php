<?php
session_start();
require_once __DIR__ . "/../../config.php";

// Redirect user to login page if not logged in or not an admin
if (!isset($_SESSION["username"])) {
  header("Location: " . BASE_URL . "/login_form.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Participants list</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />

</head>

<body>
  <?php
  require_once __DIR__ . "/../../navbar.php";
  ?>
  <h2 class="m-5 text-center">Participants list</h2>
  <div class="container">
    <div class="table-responsive">
      <table class="table table-striped table-hover" id="participants_table">
        <thead>
          <tr>
            <th></th>
            <th>Photo</th>
            <th>Name</th>
            <th>Email</th>
            <th>Register At</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"
    integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
  <script src="<?php echo BASE_URL; ?>/assets/scripts/main.js"></script>
  <script src="<?php echo BASE_URL; ?>/assets/scripts/participants/table.js"></script>

</body>

</html>