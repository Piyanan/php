<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
  <h2 class="mt-5 text-center">Registration</h2>
  <div class="container mt-5">
    <form method="post" action="register.php">
      <div class="row mb-3 justify-content-center">
        <div class="col-sm-6">
          <input type="text" class="form-control" name="username" id="username" placeholder="Username" required>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-sm-3 mb-3">
          <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
        </div>
        <div class="col-sm-3 mb-3">
          <input type="password" class="form-control" name="confirm_password" id="confirm_password"
            placeholder="Confirm Password" required>
        </div>
      </div>
      <div class="mb-3 text-center">
        <button type="submit" name="submit" class="btn btn-primary btn-lg">Register</button>
      </div>
    </form>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>
</body>

</html>