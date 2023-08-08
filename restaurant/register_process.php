<?php
session_start();

// Check if the form is submitted for registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve user inputs
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $address = $_POST['address'];

    // Validate inputs
    if (empty($username) || empty($password) || empty($confirmPassword) || empty($fullName) || empty($email) || empty($phoneNumber) || empty($address)) {
        // Display an error message if any field is empty
        $_SESSION['error_message'] = 'All fields are required.';
        header('Location: register.php');
        exit;
    } elseif ($password !== $confirmPassword) {
        // Display an error message if passwords do not match
        $_SESSION['error_message'] = 'Passwords do not match.';
        header('Location: register.php');
        exit;
    }

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Establish the database connection
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'restaurant_db';

    // Create a connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check the connection
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // Check if the username is already taken
    $usernameCheckQuery = "SELECT * FROM users WHERE username = '$username'";
    $usernameCheckResult = mysqli_query($conn, $usernameCheckQuery);

    if (mysqli_num_rows($usernameCheckResult) > 0) {
        // Display an error message if the username is taken
        $_SESSION['error_message'] = 'Username is already taken. Please choose a different username.';
        header('Location: register.php');
        exit;
    }

    // Insert the user data into the users table
    $insertUserQuery = "INSERT INTO users (username, password, role, full_name, email, phone_number, address) VALUES ('$username', '$hashedPassword', 'customer', '$fullName', '$email', '$phoneNumber', '$address')";

    if (mysqli_query($conn, $insertUserQuery)) {
        // Registration successful
        $_SESSION['success_message'] = 'Registration successful! You can now log in with your credentials.';
        header('Location: login.php');
        exit;
    } else {
        // Display an error message if the insertion fails
        $_SESSION['error_message'] = 'Error: ' . mysqli_error($conn);
        header('Location: register.php');
        exit;
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // If the form is not submitted, redirect back to the register page
    header('Location: register.php');
    exit;
}
?>
