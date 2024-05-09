<?php

$hostName = "localhost";
$dbUser = "root";
$dbPassword = "";
$dbName = "login-register";
$conn =mysqli_connect($hostName, $dbUser, $dbPassword, $dbName );
if($conn->connect_error) {
    die("Something wrong" . $conn->connect_errno); 
}
?>