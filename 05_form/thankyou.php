<?php

// Include config file
require_once "config.php";

$participant_id = $_GET['participant_id'];
$sql = "SELECT name FROM participants WHERE participant_id = ?";
$stmt = mysqli_prepare($mysqli, $sql);
$stmt->bind_param("i", $participant_id);
$stmt->execute();
$stmt->bind_result($name);
if (!$stmt->fetch()) {
  echo "Error fetching participant data from database.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Physics Conference Registration</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <h2 class="m-5 text-center">Thank you
      <?php echo $name; ?>
    </h2>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
</body>

</html>