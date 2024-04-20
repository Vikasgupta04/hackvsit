<?php
require "partials/connection.php";
session_start();
if($_SERVER['REQUEST_METHOD'] == "POST") {
$login_email = $_POST['email'];
$login_password = $_POST['password'];
$user_check_query = "SELECT * FROM `users` WHERE `email` = '$login_email';";
$result = mysqli_query($conn, $user_check_query);

if (mysqli_num_rows($result) == 1) 
{
    $row = mysqli_fetch_assoc($result);
    $hashed_password = $row['password'];
    echo''.$hashed_password.'';

    // Verify the password
    if (password_verify($login_password, $hashed_password)) 
    {
        $_SESSION['email'] = $login_email;
        echo "<br>";
        echo $_SESSION['email'];
        header("location: ../templates/home.html"); 
        exit();
    } 
    else 
    {
        // Password is incorrect
        echo "Invalid username or password";
    }
}
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DiagnoSphere Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <div class="container bg-dark text-light my-5 w-25 p-4 rounded">
        <h3 class="mb-4 text-center">Login to DiagnoSphere</h3>
        <form method = "post">
  <div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="email" class="form-control mb-3" id="email" aria-describedby="emailHelp" name="email" required>
  <div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input type="password" class="form-control" id="password" name="password" required>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
  <span>Dont have an account? <a href="signup.php">Signup</a></span>

</form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>