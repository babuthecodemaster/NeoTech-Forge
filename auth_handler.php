<?php
session_start();
include 'db_connect.php'; // Include the database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action']; // Determine if it's a login or sign-up request

    if ($action === 'login') {
        // Handle login
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);

        // Query to check if the user exists
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];

                // Redirect to the homepage or dashboard
                header('Location: index.html');
                exit();
            } else {
                // Redirect back with an error message
                header('Location: login.html?error=Invalid email or password');
                exit();
            }
        } else {
            // Redirect back with an error message
            header('Location: login.html?error=Invalid email or password');
            exit();
        }
    } elseif ($action === 'signup') {
        // Handle sign-up
        $name = $conn->real_escape_string($_POST['name']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash the password

        // Check if the email already exists
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Redirect back with an error message
            header('Location: login.html?error=Email already exists');
            exit();
        }

        // Insert the new user into the database
        $query = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";
        if ($conn->query($query) === TRUE) {
            // Redirect to login page with success message
            header('Location: login.html?success=Account created successfully');
            exit();
        } else {
            // Redirect back with an error message
            header('Location: login.html?error=Failed to create account');
            exit();
        }
    } else {
        // Redirect to login page if action is invalid
        header('Location: login.html');
        exit();
    }
} else {
    // Redirect to login page if accessed directly
    header('Location: login.html');
    exit();
}
