<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is a manager and if the item_id is provided in the URL
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager' || !isset($_GET['item_id'])) {
    $_SESSION['error_message'] = 'You do not have permission to access this page.';
    header('Location: menu.php');
    exit;  
}

// Get the item_id from the URL
$itemID = $_GET['item_id'];

// Establish the database connection (Ensure you include 'connection.php' here)
include 'connection.php';

// Fetch the menu item from the database
$menuItemQuery = "SELECT * FROM menu_item WHERE item_id = $itemID";
$menuItemResult = mysqli_query($conn, $menuItemQuery);

if (!$menuItemResult || mysqli_num_rows($menuItemResult) == 0) {
    $_SESSION['error_message'] = 'Menu item not found.';
    header('Location: menu.php');
    exit;
}

$menuItem = mysqli_fetch_assoc($menuItemResult);

// Check if the form is submitted to update the menu item
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = $_POST['new_name'];
    $newDescription = $_POST['new_description'];
    $newPrice = $_POST['new_price'];

    // Validate the input values (you can add more validation here)
    if (empty($newName) || empty($newDescription) || !is_numeric($newPrice) || $newPrice <= 0) {
        $_SESSION['error_message'] = 'Invalid input values. Please provide valid values.';
        header('Location: edit_item.php?item_id=' . $itemID);
        exit;
    }

    // Update the menu item in the database
    $updateQuery = "UPDATE menu_item SET name = '$newName', description = '$newDescription', price = '$newPrice' WHERE item_id = $itemID";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $_SESSION['success_message'] = 'Menu item updated successfully.';
        header('Location: menu.php');
        exit;
    } else {
        $_SESSION['error_message'] = 'An error occurred while updating the menu item.';
        header('Location: edit_item.php?item_id=' . $itemID);
        exit;
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safari Flavors - Edit Item</title>
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

        .edit-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .edit-form h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333;
        }

        .edit-form label {
            font-size: 16px;
            color: #666;
        }

        .edit-form input[type="text"],
        .edit-form input[type="number"],
        .edit-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            color: #333;
        }

        .edit-form textarea {
            resize: vertical;
        }

        .edit-form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .edit-form input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Edit Menu Item</h2>
    <div class="edit-form">
        <form method="POST" action="edit_item.php?item_id=<?php echo $itemID; ?>">
            <label for="new_name">Name:</label>
            <input type="text" name="new_name" value="<?php echo $menuItem['name']; ?>" required><br>
            <label for="new_description">Description:</label>
            <textarea name="new_description" rows="4" required><?php echo $menuItem['description']; ?></textarea><br>
            <label for="new_price">Price:</label>
            <input type="number" name="new_price" value="<?php echo $menuItem['price']; ?>" step="0.01" required><br>
            <input type="submit" value="Update Item">
            
        </form>
    </div>
</body>
</html>
