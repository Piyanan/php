<!DOCTYPE html>
<html>

<head>
  <title>Registration Page</title>
</head>

<body>
  <h2>Registration Page</h2>
  <form method="post" action="register.php">
    <label>Username:</label>
    <input type="text" name="username" required><br><br>
    <label>Password:</label>
    <input type="password" name="password" required><br><br>
    <label>Confirm Password:</label>
    <input type="password" name="confirm_password" required><br><br>
    <input type="submit" name="submit" value="Register">
  </form>
</body>

</html>