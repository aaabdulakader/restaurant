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
    } elseif ($password !== $confirmPassword) {
        // Display an error message if passwords do not match
        $_SESSION['error_message'] = 'Passwords do not match.';
    } else {
        // Hash the password for security
        // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Establish the database connection
        $servername = 'localhost';
        $dbUsername = 'root';
        $dbPassword = '';
        $dbname = 'restaurant_db';

        // Create a connection
        $conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbname);

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
        } else {
            // Insert the user data into the users table
            $insertUserQuery = "INSERT INTO users (username, password, role, full_name, email, phone_number, address) VALUES ('$username', '$password', 'customer', '$fullName', '$email', '$phoneNumber', '$address')";

            if (mysqli_query($conn, $insertUserQuery)) {
                // Registration successful
                $_SESSION['success_message'] = 'Registration successful! You can now log in with your credentials.';
                header('Location: login.php');
                exit;
            } else {
                // Display an error message if the insertion fails
                $_SESSION['error_message'] = 'Error: ' . mysqli_error($conn);
            }
        }

        // Close the database connection
        mysqli_close($conn);
    }

    // Redirect back to the register page (whether successful or with errors)
    header('Location: register.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safari Flavors - Register</title>
    <style>
        /* Styles for the registration form */
        .register-container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
        }
        
        .register-container h2 {
            text-align: center;
        }
        
        .register-container label,
        .register-container input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .register-container input[type="submit"] {
            background-color: #ffcc00;
            color: #fff;
            border: none;
            padding: 5px 10px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }
        
        .register-container input[type="submit"]:hover {
            background-color: #ffa500;
        }
        .signIn-link{
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="register-container">
        <h2>Register</h2>
        <?php
        if (isset($_SESSION['error_message'])) {
            echo '<p style="color: red;">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']);
        }
        ?>
        <form action="register.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>
            
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            
            <input type="submit" value="Register">
        </form>

        <p class="signIn-link">
            <a href="login.php">Have an account? Login</a>
        </p>
    </div>
</body>
</html>
