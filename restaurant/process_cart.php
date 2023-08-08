<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit;
}

// Check if the form is submitted to add an item to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_item_id'])) {
    $menuItemID = $_POST['menu_item_id'];
    $quantity = $_POST['quantity'];

    // Validate the quantity to ensure it's a positive integer
    if (!is_numeric($quantity) || $quantity <= 0 || floor($quantity) != $quantity) {
        // Invalid quantity, redirect back to the menu page with an error message
        $_SESSION['error_message'] = 'Invalid quantity. Please enter a positive integer.';
        header('Location: menu.php');
        exit;
    }

    // Connect to the database
    include 'connection.php';

    // Check if the menu item exists
    $menuItemQuery = "SELECT * FROM menu_item WHERE item_id = $menuItemID";
    $menuItemResult = mysqli_query($conn, $menuItemQuery);

    if (mysqli_num_rows($menuItemResult) == 0) {
        // Menu item not found, redirect back to the menu page with an error message
        $_SESSION['error_message'] = 'Menu item not found.';
        header('Location: menu.php');
        exit;
    }

    // Check if the user already has a cart entry for this item
    $userID = $_SESSION['user_id'];
    $cartItemQuery = "SELECT * FROM cart WHERE user_id = $userID AND item_id = $menuItemID";
    $cartItemResult = mysqli_query($conn, $cartItemQuery);

    if (mysqli_num_rows($cartItemResult) > 0) {
        // Update the quantity if the item is already in the cart
        $updateQuery = "UPDATE cart SET quantity = $quantity WHERE user_id = $userID AND item_id = $menuItemID";
        mysqli_query($conn, $updateQuery);
    } else {
        // Insert the item into the cart table
        $insertQuery = "INSERT INTO cart (user_id, item_id, quantity) VALUES ($userID, $menuItemID, $quantity)";
        mysqli_query($conn, $insertQuery);
    }

    // Close the database connection
    mysqli_close($conn);

    // Redirect back to the menu page after adding the item to the cart
    header('Location: menu.php');
    exit;
} else {
    // If the form is not submitted, redirect back to the menu page
    header('Location: menu.php');
    exit;
}
