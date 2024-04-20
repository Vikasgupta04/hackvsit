<?php
$showError = false;
$showAlert = false;

if($_SERVER['REQUEST_METHOD'] == "POST") {
require "partials/connection.php";
$email = $_POST['email'];
$password = $_POST['password'];
$contact = $_POST['contact'];
$cpassword = $_POST['cpassword'];
$hospitalName = $_POST['hospitalName'];
$hospitalLocation = $_POST['hospitalLocation'];

$checkEmailQuery = "SELECT * FROM `users` WHERE `email`='$email'";
$result = mysqli_query($conn, $checkEmailQuery);
if (mysqli_num_rows($result) > 0) {
        $showError = "Email already exists";
    } 
    elseif ($password != $cpassword) {
        $showError = "Passwords do not match";
    }
    else{
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $sql = "INSERT INTO `users` (`email`, `password`, `contact`, `hospital_name`, `hospital_location`) VALUES ('$email', '$hashedPassword', '$contact', '$hospitalName', '$hospitalLocation');";
    $result = mysqli_query($conn, $sql);
    if($result){
        $showAlert = true;
        // sleep(5);
        header("location: login.php");
        }
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Diagnosphere Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <?php
    if($showError) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Failed to SignUp! </strong>' .$showError.'
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
    }
    ?>
    <?php
    if($showAlert) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>Success!</strong> You are registered.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
    }
    ?>
    <div class="container bg-dark text-light my-5 w-25 p-4 rounded">
        <h3 class="mb-4 text-center">SignUp to DiagnoSphere</h3>
        <form method = "post">
            <div>
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text text-light mb-3">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contact No.</label>
                <input type="text" class="form-control" id="contact" name="contact" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
             <div class="mb-3">
                <label for="cpassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="cpassword" name="cpassword" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="hospitalName" class="form-label">Hospital Name</label>
                <input type="text" class="form-control" id="hospitalName" name="hospitalName" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="hospitalLocation" class="form-label">Hospital Location</label>
                <input type="text" class="form-control" id="hospitalLocation" name="hospitalLocation" aria-describedby="emailHelp">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <span>Already have an account? <a href="login.php">Login</a></span>
        </form>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>