<?php
$host = "localhost";
$user = "root";  // Default WAMP user
$password = "";  // Default WAMP password (empty)
$database = "rso_events";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>