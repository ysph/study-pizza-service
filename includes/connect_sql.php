<?php
$servername = "localhost";
$username = "root";
$password = "p0g0v0rit45";
$dbname = "pizza-hut";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>