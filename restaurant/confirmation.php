<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safari Flavors - Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        h2 {
            margin-top: 50px;
        }

        .confirmation-message {
            margin-top: 20px;
            color: #006400;
            font-weight: bold;
        }

        .home-link {
            display: block;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <h2>Order Confirmation</h2>

    <?php
    // Check if there is a success message stored in the session
    if (isset($_SESSION['success_message'])) {
        echo '<div class="confirmation-message">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']); // Clear the success message after displaying it
    } else {
        // If there is no success message, redirect back to the menu page
        header('Location: menu.php');
        exit;
    }
    ?>

    <a class="home-link" href="menu.php">Back to Menu</a>
</body>
</html>
