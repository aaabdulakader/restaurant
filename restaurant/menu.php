<?php
// Start the session
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch the user's role from the session
// $userRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';


// Check if the form is submitted to add an item to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['item_id']) && isset($_SESSION['user_id'])) {
    $menuItemID = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    // Validate the quantity to ensure it's a positive integer
    if (!is_numeric($quantity) || $quantity <= 0 || floor($quantity) != $quantity) {
        // Invalid quantity, redirect back to the menu page with an error message
        $_SESSION['error_message'] = 'Invalid quantity. Please enter a positive integer.';
        header('Location: menu.php');
        exit;
    }

    // Establish the database connection (Ensure you include 'connection.php' here)
    include 'connection.php';

    // Check if the menu item exists in the database
    $menuItemQuery = "SELECT * FROM menu_item WHERE item_id = $menuItemID";
    $menuItemResult = mysqli_query($conn, $menuItemQuery);

    if (mysqli_num_rows($menuItemResult) == 0) {
        // Menu item not found, redirect back to the menu page with an error message
        $_SESSION['error_message'] = 'Menu item not found.';
        header('Location: menu.php');
        exit;
    }

    // Create or update the cart session for the user
    $userID = $_SESSION['user_id'];
    if (isset($_SESSION['cart'][$userID][$menuItemID])) {
        // Update the quantity if the item is already in the cart
        $_SESSION['cart'][$userID][$menuItemID]['quantity'] += $quantity;
    } else {
        // Add the item to the cart
        $menuItem = mysqli_fetch_assoc($menuItemResult); // Fetch the menu item details
       $_SESSION['cart'][$userID][$menuItemID] = array(
            'name' => $menuItem['name'],
            'price' => $menuItem['price'],
            'quantity' => $quantity
        );

    }

    var_dump($_SESSION['cart']);

    // Close the database connection
    mysqli_close($conn);

    // Redirect back to the menu page after adding the item to the cart
    header('Location: menu.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safari Flavors - Menu</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

header {
    background-color: #4CAF50;
    padding: 20px;
    text-align: center;
    color: white;
}

.menu-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin: 20px;
}

.menu-item {
    width: 300px;
    border: 1px solid #ccc;
    padding: 10px;
    margin: 10px;
    border-radius: 5px;
    background-color: #f8f8f8;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.menu-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
}

.menu-item h4 {
    margin-top: 0;
    font-size: 18px;
    color: #333;
}

.menu-item p {
    margin-bottom: 10px;
    font-size: 16px;
    color: #666;
}

.add-to-cart-form {
    display: flex;
    align-items: center;
    
}

.add-to-cart {
    background-color: #ffcc00;
    color: #fff;
    border: none;
    padding: 5px 10px;
    text-decoration: none;
    font-weight: bold;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.add-to-cart:hover {
    background-color: #ffa500;
}

.quantity-input {
    width: 50px;
    margin-left: 10px;
    padding: 5px;
}

.update-menu-button {
    display: flex;
    justify-content: center;
    margin: 20px;
}

.update-menu-button a {
    padding: 10px 20px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.update-menu-button a:hover {
    background-color: #45a049;
}
       .edit-button {
            /* background-color: #4CAF50; */
            /* color: #000; */
            border: none;
            padding: 5px 10px;
            /* text-decoration: none; */
            font-weight: bold;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
            margin-left: auto;
        }

        .edit-button:hover {
            background-color: #ffcc00;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
   



    <?php
    // Establish the database connection
    include 'connection.php'; // Assuming this file includes the connection to the database

    // Fetch the menu items from the database and store them in the session
    $menuItems = array();
    $query = "SELECT * FROM menu_item";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $menuItems[] = $row;
        }
        $_SESSION['menu_items'] = $menuItems;
    } else {
        echo "Error fetching menu items: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
    ?>

<div class="menu-container">
        <?php
        // Display the menu items from the session
        foreach ($menuItems as $menuItem) {
            echo '<div class="menu-item">';
            echo '<h4>' . $menuItem['name'] . '</h4>';
            echo '<p>Price: $' . $menuItem['price'] . '</p>';
            echo '<form class="add-to-cart-form" method="POST" action="menu.php">';
            echo '<input type="hidden" name="item_id" value="' . $menuItem['item_id'] . '">';
            echo '<input type="number" name="quantity" class="quantity-input" min="1" value="1">';
            echo '<input type="submit" class="add-to-cart" value="Add to Cart">';
            if ($userRole === 'manager') {
                echo '<a class="edit-button" href="edit_item.php?item_id=' . $menuItem['item_id'] . '">Edit</a>';
            }
            echo '</form>';
            echo '</div>';
        }
        ?>
    </div>

        <?php
    // Display the "Update Menu" button for managers
    if ($userRole === 'manager') {
        echo '<div class="update-menu-button">';
        echo '<a href="update_menu.php">Update Menu</a>';
        echo '</div>';
    }
    ?>
</body>
</html>
