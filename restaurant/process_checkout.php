<?php
session_start();



include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the values from the form

    $deliveryOption = $_POST['delivery_option'];
    $cardNumber = $_POST['card_number'];
    $cardExpiry = $_POST['card_expiry'];
    $cardCVV = $_POST['card_cvv'];
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // If the user is not logged in, redirect back to the menu page with an error message
        $_SESSION['error_message'] = 'Please log in to proceed with the checkout.';
        header('Location: menu.php');
        exit;
    }

    // Check if the cart exists in the session
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        // If the cart is empty, redirect back to the menu page with an error message
        $_SESSION['error_message'] = 'Your cart is empty.';
        header('Location: menu.php');
        exit;
    }

    // Get the user ID
    $userID = $_SESSION['user_id'];


    // Initialize the pickup time and delivery address variables
    $pickupTime = null;
    $deliveryAddress = null;

    if ($deliveryOption === 'pickup') {
        // If the user chose pickup, get the pickup time from the form
       $pickupTimeUnix = $_POST['pickup_time'];

        // Convert the Unix timestamp to a valid MySQL time format (Y-m-d H:i:s)
        $pickupTime = date('Y-m-d H:i:s', $pickupTimeUnix);

    } else if ($deliveryOption === 'delivery') {
        // If the user chose delivery, get the delivery address from the form
        $deliveryAddress = $_POST['delivery_address'];
    }

    // Calculate the total cost
    $totalCost = 0;
    foreach ($_SESSION['cart'] as $userID => $userCart) {
        foreach ($userCart as $itemID => $item) {
            $itemQuantity = $item['quantity'];
            $itemPrice = $item['price'];
            $itemTotal = $itemQuantity * $itemPrice;
            $totalCost += $itemTotal;
        }
    }

    // Get the current date and time
    $orderDate = date('Y-m-d H:i:s');

    // Set the order status to 'pending' for now (you can update this later based on your order processing flow)
    $orderStatus = 'pending';

    // Store the order information in the orders table
    $insertOrderQuery = "INSERT INTO orders (user_id, order_date, total_cost, delivery_option, pickup_time, delivery_address, payment_info, order_status, created_at, updated_at)
                         VALUES ('$userID', '$orderDate', '$totalCost', '$deliveryOption', '$pickupTime', '$deliveryAddress', '$cardNumber', '$orderStatus', NOW(), NOW())";
    $insertOrderResult = mysqli_query($conn, $insertOrderQuery);

    if ($insertOrderResult) {
        // Order successfully placed, clear the cart and redirect to a confirmation page
        unset($_SESSION['cart']);
        $_SESSION['success_message'] = 'Order successfully placed. Thank you for your purchase!';
        header('Location: confirmation.php');
        exit;
    } else {
        // If there was an error inserting the order, redirect back to the checkout page with an error message
    $_SESSION['error_message'] = 'An error occurred while processing your order. Please try again later.';
    $_SESSION['error_details'] = mysqli_error($conn); // Add this line to store the specific error message for debugging
    header('Location: checkout.php');

    exit;
    }
} else {
    // If the form was not submitted through POST, redirect back to the checkout page
    header('Location: process_checkout.php');
    exit;
}
