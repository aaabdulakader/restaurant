<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit;
}

// Get the user ID from the session
$userID = $_SESSION['user_id'];

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

// Query the database to retrieve the user information
$sql = "SELECT * FROM customer WHERE user_id = $userID";
$result = $conn->query($sql);

// Check if the user is found in the database
if ($result->num_rows == 1) {
    // User found, fetch the profile information
    $row = $result->fetch_assoc();
    $fullName = $row['full_name'];
    $email = $row['email'];
    $phoneNumber = $row['phone_number'];
    $address = $row['address'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .profile-card {
            width: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .profile-picture {
           max-width: 40%;
           height: auto;
           border-radius: 50%;
           margin-bottom: 20px;
        }

        .signout-button {
            margin-top: 20px;
            background-color: #ffcc00;
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }

        .edit-button {
            background-color: #ffcc00;
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }

        .edit-button:hover,
        .signout-button:hover {
            background-color: #ffa500;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="profile-card">
        <img class="profile-picture" src="images/1168742.png">
        <h1>Welcome, <?php echo $fullName; ?>!</h1>
        <p>Email: <?php echo $email; ?></p>
        <p>Phone Number: <?php echo $phoneNumber; ?></p>
        <p>Address: <?php echo $address; ?></p>
        <a href="edit_profile.php" class="edit-button">Edit Profile</a>
        <a href="logout.php" class="signout-button">Sign Out</a>
    </div>

</body>
</html>

<?php
} else {
    // User not found, display an error message
    echo 'User not found.';
}

// Close the database connection
$conn->close();
?>
