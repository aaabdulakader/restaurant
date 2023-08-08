<?php

    // connect to the database
    include 'connection.php';

    // start the session
    // session_start();

    // Get the user ID and role
$userID = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Retrieve the user's role from the database
$userRole = null;
if ($userID) {
    $roleQuery = "SELECT role FROM users WHERE user_id = $userID";
    $roleResult = mysqli_query($conn, $roleQuery);
    $userRole = mysqli_fetch_assoc($roleResult)['role'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safari Flavors</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Navbar styles */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 999;
            font-family: Arial, Helvetica, sans-serif;
        }

        nav ul {
            list-style-type: none;
            display: flex;
            gap: 20px;
        }

        nav ul li {
            margin-right: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }

        nav ul li a:hover {
            color: #ffcc00;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }

        /* Content styles */
        body {
            padding-top: 60px; /* Adjust this value to give space for the fixed navbar */
        }
    </style>
</head>
<body>
    <nav>
        <a href="#">Safari Flavors</a>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="about.php">About</a></li>

            <!-- If logged in as a manager or, display reservations else reserve -->
            

            <li><a href="reserve.php">Reserve</a></li>

            <!-- If logged in as a manager, display reservations else reserve -->
           
                <li><a href="reservations.php">Reservations</a></li>
         

            <!-- orders -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="orders.php">Orders</a></li>
            <?php endif; ?>
            

            <!-- If logged in, display profile link -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="profile.php">Profile</a></li>
            <?php endif; ?>

            <!-- Cart link -->
            <li><a href="cart.php">Cart</a></li>
        </ul>

        <!-- If logged in, display logout link; otherwise, display login link -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </nav>
</body>
</html>
