<?php
// config.php
$host = "localhost";
$user = "root";       // change if using hosting/db user
$pass = "";           // db password
$dbname = "client_website";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
