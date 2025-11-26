<?php
/**
 * Database Configuration Template
 * 
 * Instructions:
 * 1. Copy this file and rename it to 'db_connect.php'
 * 2. Update the values below with your actual database credentials
 * 3. Never commit db_connect.php to version control
 */

$host = 'localhost'; // Database host (e.g., localhost, 127.0.0.1, or remote host)
$db = 'neotech_forge'; // Database name
$user = 'your_database_username'; // Database username
$pass = 'your_database_password'; // Database password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Connection successful (remove this line in production)
// echo "Connected successfully to the database.";
?>
