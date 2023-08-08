<?php
session_start();
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect back to the login page with an error message
    $_SESSION['error_message'] = 'Please log in to rate your orders.';
    header('Location: login.php');
    exit;
}

// Get the user ID and role
$userID = $_SESSION['user_id'];

// Check if the user has access to rate orders (only regular users can rate their own orders)
$userRoleQuery = "SELECT role FROM users WHERE user_id = $userID";
$userRoleResult = mysqli_query($conn, $userRoleQuery);
$userRole = mysqli_fetch_assoc($userRoleResult)['role'];

// If the user is not a regular user, redirect back to the orders page with an error message
if ($userRole !== 'user') {
    $_SESSION['error_message'] = 'You are not authorized to rate orders.';
    header('Location: orders.php');
    exit;
}

// Check if the form is submitted for rating an order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['rating'])) {
    $orderID = $_POST['order_id'];
    $rating = $_POST['rating'];
    $feedback = $_POST['feedback'];

    // Validate the rating to ensure it's within the range of 1-5
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        // Invalid rating, redirect back to the rate page with an error message
        $_SESSION['error_message'] = 'Invalid rating. Please select a rating between 1 and 5.';
        header('Location: rate.php');
        exit;
    }

    // Update the rating and feedback in the orders table for the specific order and user
    $updateRatingQuery = "UPDATE orders SET rate = $rating, feedback = '$feedback' WHERE order_id = $orderID AND user_id = $userID";
    if (mysqli_query($conn, $updateRatingQuery)) {
        // Success! Redirect back to the orders page with a success message
        $_SESSION['success_message'] = 'Thank you for rating your order.';
        header('Location: orders.php');
        exit;
    } else {
        // If there was an error updating the rating and feedback, redirect back to the rate page with an error message
        $_SESSION['error_message'] = 'An error occurred while rating your order. Please try again later.';
        header('Location: rate.php');
        exit;
    }
}

// Retrieve the user's orders that are eligible for rating (orders with status "completed" and haven't been rated)
$userOrdersQuery = "SELECT * FROM orders WHERE user_id = $userID AND order_status = 'completed' AND rate IS NULL";
$userOrdersResult = mysqli_query($conn, $userOrdersQuery);

// Check if there are any orders eligible for rating
$eligibleOrders = array();
if (mysqli_num_rows($userOrdersResult) > 0) {
    // Loop through the orders and store them in the $eligibleOrders array
    while ($order = mysqli_fetch_assoc($userOrdersResult)) {
        $eligibleOrders[] = $order;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safari Flavors - Rate Your Orders</title>
    <style>
        /* Styles for the rating page */
        body {
            font-family: Arial, sans-serif;
        }

        .rating-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        h2 {
            text-align: center;
        }

        .order {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .rating-input {
            display: inline-block;
            margin-right: 10px;
        }

        .feedback-input {
            display: block;
            margin-top: 10px;
            width: 100%;
            padding: 5px;
        }

        .submit-button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #ffcc00;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .submit-button:hover {
            background-color: #ffa500;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="rating-container">
        <h2>Rate Your Orders</h2>
        <?php
        if (count($eligibleOrders) > 0) {
            // Loop through the eligible orders and display the rating form
            foreach ($eligibleOrders as $order) {
                $orderID = $order['order_id'];
                $orderDate = $order['order_date'];
                $totalCost = $order['total_cost'];
                $deliveryOption = $order['delivery_option'];
                $pickupTime = $order['pickup_time'];
                $deliveryAddress = $order['delivery_address'];
                $paymentInfo = $order['payment_info'];

                echo '<div class="order">';
                echo '<p><strong>Order ID:</strong> ' . $orderID . '</p>';
                echo '<p><strong>Order Date:</strong> ' . $orderDate . '</p>';
                echo '<p><strong>Total Cost:</strong> $' . $totalCost . '</p>';
                echo '<p><strong>Delivery Option:</strong> ' . $deliveryOption . '</p>';
                if ($deliveryOption === 'pickup') {
                    echo '<p><strong>Pickup Time:</strong> ' . $pickupTime . '</p>';
                } else {
                    echo '<p><strong>Delivery Address:</strong> ' . $deliveryAddress . '</p>';
                }
                echo '<p><strong>Payment Information:</strong> ' . $paymentInfo . '</p>';

                // Display the rating form
                echo '<form method="post" action="rate.php">';
                echo '<input type="hidden" name="order_id" value="' . $orderID . '">';
                echo '<div class="rating-input">';
                echo '<label for="rating">Rate this order (1-5): </label>';
                echo '<input type="number" name="rating" id="rating" min="1" max="5" required>';
                echo '</div>';
                echo '<textarea name="feedback" class="feedback-input" placeholder="Optional feedback"></textarea>';
                echo '<button type="submit" class="submit-button">Submit Rating</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo '<p>No orders eligible for rating at the moment.</p>';
        }
        ?>
    </div>
</body>
</html>
