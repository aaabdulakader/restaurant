<?php
session_start();

include 'connection.php';

// Check if there are any error messages stored in the session
if (isset($_SESSION['error_message'])) {
    echo '<div style="color: red; text-align: center; font-weight: bold;">';
    echo $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Clear the error message after displaying it
    echo '</div>';
}

// Check if there are any error details stored in the session (for debugging purposes)
if (isset($_SESSION['error_details'])) {
    echo '<div style="color: red; text-align: center; font-weight: bold;">';
    echo 'Error Details: ' . $_SESSION['error_details'];
    unset($_SESSION['error_details']); // Clear the error details after displaying them
    echo '</div>';
}
?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// session_start();
include 'connection.php';



// Retrieve the user's address from the database
$userID = $_SESSION['user_id'];
$userQuery = "SELECT * FROM users WHERE user_id = $userID";
$userResult = mysqli_query($conn, $userQuery);

if (mysqli_num_rows($userResult) == 0) {
    // User not found, redirect back to the menu page with an error message
    $_SESSION['error_message'] = 'User not found.';
    header('Location: menu.php');
    exit;
} else {
    $user = mysqli_fetch_assoc($userResult);
    $userAddress = $user['address'];
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Safari Flavors - Checkout</title>
        <style>
                    body {
                        font-family: Arial, sans-serif;
                    }
            
                    h2 {
                        text-align: center;
                    }
            
                    .checkout-form {
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 20px;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                    }
            
                    .checkout-form h3 {
                        margin-top: 20px;
                        margin-bottom: 10px;
                    }
            
                    .checkout-form label {
                        display: block;
                        margin-bottom: 5px;
                    }
            
                    .checkout-form input[type="text"],
                    .checkout-form select {
                        width: 100%;
                        padding: 10px;
                        margin-bottom: 10px;
                        border: 1px solid #ccc;
                        border-radius: 3px;
                    }
            
                    .checkout-form input[type="radio"] {
                        margin-right: 5px;
                    }
            
                    .checkout-form input[type="submit"] {
                        background-color: #ffcc00;
                        color: #fff;
                        border: none;
                        padding: 10px 20px;
                        font-weight: bold;
                        cursor: pointer;
                        border-radius: 5px;
                    }
            
                    .checkout-form input[type="submit"]:hover {
                        background-color: #ffa500;
                    }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <h2>Checkout</h2>
    <div class="checkout-form">
        <h3>Items in Cart:</h3>
        <?php
        if (isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
            $totalCost = 0;

            // Loop through each user's cart items
            foreach ($cart as $userID => $userCart) {
                // Loop through each item in the cart
                foreach ($userCart as $itemID => $item) {
                    $itemName = $item['name'];
                    $itemQuantity = $item['quantity'];
                    $itemPrice = $item['price'];
                    $itemTotal = $itemQuantity * $itemPrice;

                    echo '<p>' . $itemName . ' x ' . $itemQuantity . ' - $' . $itemTotal . '</p>';

                    // Calculate the total cost
                    $totalCost += $itemTotal;
                }
            }
        }
        ?>
        <h3>Total Cost: $<?php echo $totalCost; ?></h3>

      <form action="process_checkout.php" method="POST">
            <h3>Choose Pickup or Delivery:</h3>
            <input type="radio" name="delivery_option" value="pickup" > Pickup<br>
            <input type="radio" name="delivery_option" value="delivery"> Delivery<br>

            <div id="pickup_times" >
                <h3>Pickup Times:</h3>
                <!-- Assume you have an array of available pickup times -->
                <select name="pickup_time">
                    <!-- Display the time options in 20 min intervals from the current time -->
                    <?php
                    $currentTime = time();
                    $pickupTimes = array();
                    $pickupTimes[] = $currentTime;
                    for ($i = 1; $i <= 12; $i++) {
                        $pickupTimes[] = $currentTime + ($i * 20 * 60);
                    }

                    foreach ($pickupTimes as $pickupTime) {
                        echo '<option value="' . $pickupTime . '">' . date('h:i A', $pickupTime) . '</option>';
                    }
                    ?>
                </select>
            </div>

            <div id="delivery_address" style="display: none;">
                <h3>Delivery Address:</h3>
                <input type="text" name="delivery_address" value="<?php echo $userAddress; ?>"><br>
            </div>

            <h3>Payment Information:</h3>
            <label for="card_number">Card Number:</label>
            <input type="text" name="card_number" ><br>

            <label for="card_expiry">Card Expiry:</label>
            <input type="text" name="card_expiry" ><br>

            <label for="card_cvv">Card CVV:</label>
            <input type="text" name="card_cvv" ><br>
            <input type="submit" value="Place Order">
        </form>

        <script>
            // Function to show/hide pickup times and delivery address based on the selected option
            function toggleOptions() {
                const pickupTimesDiv = document.getElementById('pickup_times');
                const deliveryAddressDiv = document.getElementById('delivery_address');
                const pickupRadio = document.querySelector('input[name="delivery_option"][value="pickup"]');
                const deliveryRadio = document.querySelector('input[name="delivery_option"][value="delivery"]');

                pickupRadio.addEventListener('change', () => {
                    pickupTimesDiv.style.display = 'block';
                    deliveryAddressDiv.style.display = 'none';
                });

                deliveryRadio.addEventListener('change', () => {
                    pickupTimesDiv.style.display = 'none';
                    deliveryAddressDiv.style.display = 'block';

                    pickupTimeSelect.value = <?php echo $pickupTimes[0]; ?>; // Set the default pickup time to the first option
                });
            }

            // Call the toggleOptions function when the document is ready
            document.addEventListener("DOMContentLoaded", toggleOptions);
        </script>
    </div>
</body>
</html>
