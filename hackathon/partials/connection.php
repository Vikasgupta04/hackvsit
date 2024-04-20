<?php
$servername = "localhost";
$username = "root";
$password = "12345";
$dbname = "diagnosphere";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn){
    die("". mysqli_connect_error());
}
else{
    // echo "connected<br>";
}