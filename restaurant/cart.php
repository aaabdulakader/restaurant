
<?php
  session_start();
    include 'connection.php';

//     error_reporting(E_ALL);
// ini_set('display_errors', 1);

    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safari Flavors - Cart</title>
    <style>
        /* Styles for the cart */
        .cart-container {
            max-width: 100%;
            display: flex;
            flex-direction: column;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }
        
        .cart-item {
            width: 300px;
            padding: 5px;
            border-bottom: 1px solid #ccc;
        }
        
        .cart-item h4 {
            margin-top: 0;
        }
        
        .cart-item p {
            display: inline;
            margin-bottom: 5px;
        }
        
        .cart-total {
            margin-top: 20px;
            text-align: center;
            max-width: 100%;
        }

        .cart-total p {
            margin-bottom: 10px;
        }
        
        .checkout-button {
            max-width: 100%;
            background-color: #ffcc00;
            color: #fff;
            border: none;
            padding: 5px 10px;
            margin-top: 20px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }
        
        .checkout-button:hover {
            background-color: #ffa500;
        }
        
        .quantity-dropdown {
            margin-bottom: 10px;
        }
        
        .delete-button {
            background-color: #ff0000;
            background-color: #fff;
            border: none;
            padding: 5px 0px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            margin-right: 5px;
        }
        
        .delete-button:hover {
            background-color: #cc0000;
        }

        .clear-cart-button {
            background-color: #ff0000;
            color: #fff;
            border: none;
            padding: 5px 10px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
        }
        .quantity-input {
            max-width: 25px;
            margin-right: 5px;
        }

        .clear-cart-button:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
    <?php
  
    include 'navbar.php';
    
    // Clear the cart session when the "Clear Cart" button is clicked
    if (isset($_POST['clear_cart'])) {
        unset($_SESSION['cart']);
    }
    ?>

   <div class="cart-container">
    <?php
    // Check if the cart exists in the session
    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];

        // Check if the cart is not empty
        if (!empty($cart)) {

            $totalCost = 0;

            // Loop through each user's cart items
            foreach ($cart as $userID => $userCart) {
                // Loop through each item in the cart
                foreach ($userCart as $itemID => $item) {
                    $itemName = $item['name'];
                    $itemQuantity = $item['quantity'];
                    $itemPrice = $item['price'];
                    $itemTotal = $itemQuantity * $itemPrice;

                    echo '<div class="cart-item">';
                    echo '<h4>' . $itemName . '</h4>';
                    echo '<p>$' . $itemPrice . '</p>';
                    echo '<form method="POST" action="cart.php">';
                    echo '<input type="hidden" name="item_id[]" value="' . $itemID . '">'; // Include the item_id as a hidden input
                    echo 'Quantity: <input type="number" name="quantity[]" class="quantity-input" value="' . $itemQuantity . '" min="1">'; // Input field for quantity
                    echo '</form>';
                    echo '<form method="POST" action="cart.php">'; // Form for delete button
                    echo '<input type="hidden" name="item_id" value="' . $itemID . '">'; // Include the item_id as a hidden input
                    echo '<input type="submit" name="delete_item" value="Delete" class="delete-button">'; // Delete button
                    echo '</form>';
                    echo '</div>';

                    // Calculate the total cost
                    $totalCost += $itemTotal;
                }
            }

            

            echo '</div>';
            // Display the total cost and checkout button
            echo '<div class="cart-total">';
            echo '<h3>Cart Total</h3>';
            echo '<p>Total Cost: $' . $totalCost . '</p>';
            echo '<a href="checkout.php" class="checkout-button">Checkout</a>';
        } else {
            echo '<p>Your cart is empty.</p>';
        }
    } else {
        echo '<p>Your cart is empty.</p>';
    }
    ?>
</div>

<div class="cart-total">
    <?php
    if (!empty($cart)) {
        // "Clear Cart" button form
        echo '<form method="POST">';
        echo '<input type="submit" name="clear_cart" class="clear-cart-button" value="Clear Cart">';
        echo '</form>';
    }
    ?>
</div>

<?php
// Handle quantity update when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id']) && isset($_POST['quantity'])) {
    $itemIDs = $_POST['item_id'];
    $newQuantities = $_POST['quantity'];

    // Update the quantities in the session
    foreach ($itemIDs as $key => $itemID) {
        $newQuantity = $newQuantities[$key];
        // Validate the new quantity to ensure it's a positive integer
        if (is_numeric($newQuantity) && $newQuantity > 0 && floor($newQuantity) == $newQuantity) {
            $_SESSION['cart'][$userID][$itemID]['quantity'] = $newQuantity;
        }
    }
}

// Handle item deletion when the delete button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item']) && isset($_POST['item_id'])) {
    $itemID = $_POST['item_id'];
    // Check if the item exists in the cart
    if (isset($_SESSION['cart'][$userID][$itemID])) {
        // Remove the item from the cart
        unset($_SESSION['cart'][$userID][$itemID]);
    }
}
?>



</div>

</body>
</html>

