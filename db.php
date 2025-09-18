<?php
$host = "localhost";
$user = "root";
$password = "";  // <-- empty for XAMPP default
$database = "hospital_db"; // make sure database name is correct

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
