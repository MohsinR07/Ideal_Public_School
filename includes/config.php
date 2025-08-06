<?php
$host = "sql300.infinityfree.com";
$user = "if0_39578464";
$password = "k7ZQhN6ngoa8K9b";
$database = "if0_39578464_Ideal_db";

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
