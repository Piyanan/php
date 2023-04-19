<?php
$error = null;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Include config file
  require_once "config.php";
  require_once "functions.php";

  // Get form data
  sanitizeXSS();
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $address = $_POST['address'];
  $photo = $_FILES['photo']['name'];
  $type = $_POST['type'];

  // Validate form data
  if (empty($name)) {
    $error['name'] = "Please fill your name.";
  }
  if (empty($email)) {
    $error['email'] = "Please fill your email.";
  }
  if (empty($phone)) {
    $error['phone'] = "Please fill your phone number.";
  }
  if (empty($address)) {
    $error['address'] = "Please fill your address.";
  }
  if (empty($photo)) {
    $error['photo'] = "Please upload your photo.";
  }
  if (empty($type)) {
    $error['type'] = "Please select your type type.";
  }

  // prepare statement
  $stmt = mysqli_prepare($mysqli, "INSERT INTO participants (name, email, phone, address, type) VALUES (?, ?, ?, ?, ?)");
  // bind parameters
  $stmt->bind_param("sssss", $name, $email, $phone, $address, $type);
  if ($stmt->execute()) {
    // Upload personal photo
    $target_dir = "./uploads/";
    $target_file = $target_dir . $stmt->insert_id . ".jpg";
    if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
      echo "Error uploading photo : " . $_FILES["photo"]["error"] . "";
    }

    // Redirect to thank you page
    header("Location: thankyou.php?participant_id=" . $stmt->insert_id);
  } else {
    echo "Error: " . $stmt->error;
  }

  // Close database connection
  $stmt->close();
  mysqli_close($mysqli);

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
  <h2 class="m-5 text-center">Physics Conference Registration</h2>
  <div class="container">
    <form action="" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" name="name" id="name" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" name="email" id="email" placeholder="email@gmail.com" required>
      </div>
      <div class="mb-3">
        <label for="phone" class="form-label">Phone number</label>
        <div class="input-group">
          <span class="input-group-text" id="nation_tel">+66</span>
          <input type="tel" class="form-control" name="phone" id="phone" pattern="[0-9]{8+}" aria-label="Phone"
            aria-describedby="nation_tel" placeholder="22015726" required>
        </div>
      </div>
      <div class="mb-3">
        <label for="address">Address</label>
        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
      </div>
      <div class="mb-3">
        <label for="photo" class="form-label">Personal Photo</label>
        <input type="file" class="form-control" name="photo" id="photo" accept="image/jpeg" required>
      </div>
      <div class="mb-3">
        <label for="type" class="form-label">Register as</label>
        <select class="form-select" name="type" id="type" required>
          <option selected>Please select</option>
          <option value="member">Member</option>
          <option value="vip">VIP</option>
          <option value="visitor">Visitor</option>
        </select>
      </div>
      <button type="submit" class="btn btn-lg btn-primary">Register</button>
    </form>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
</body>

</html>