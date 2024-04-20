<?php
session_start();
require "partials/connection.php";
session_destroy();
header("location: login.php");
exit();