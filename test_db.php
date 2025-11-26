<?php
include 'db_connect.php';

if ($conn->ping()) {
    echo "Database connection is working!";
} else {
    echo "Database connection failed: " . $conn->error;
}

$conn->close();
?>
