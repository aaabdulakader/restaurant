<?php
session_start();




// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the form data (you can add more validation if needed)
    if (empty($username) || empty($password)) {
        // Display an error message if any field is empty
        echo 'Please fill in all fields.';
    } else {
        // Connect to the database
        $servername = 'localhost';
        $usernameDB = 'root';
        $passwordDB = '';
        $dbname = 'restaurant_db';

        $conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);

        // Check the database connection
        if ($conn->connect_error) {
            die('Connection failed: ' . $conn->connect_error);
        }

        // Prepare the SQL statement to check if the user exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        

        if ($result->num_rows == 1) {
            // User found, redirect to the profile page and store the user ID in the session
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['role'] = $row['role'];
            header('Location: index.php');
            exit;
        } else {
            // User not found, display an error message
            echo 'Invalid username or password.';
        }

        // Close the database connection
        $conn->close();
    }
}
?>
