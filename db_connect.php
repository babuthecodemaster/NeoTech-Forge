<?php
$host = 'localhost'; // Database host
$db = 'neotech_forge'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Connection successful
echo "Connected successfully to the database.";
?>
