<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is a manager
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    $_SESSION['error_message'] = 'You do not have permission to access this page.';
    header('Location: menu.php');
    exit;
}

// Establish the database connection 
include 'connection.php';

// Handle item deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item_id'])) {
    $deleteItemID = $_POST['delete_item_id'];

    // Delete the menu item from the database
    $deleteQuery = "DELETE FROM menu_item WHERE item_id = $deleteItemID";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {
        $_SESSION['success_message'] = 'Menu item deleted successfully.';
    } else {
        $_SESSION['error_message'] = 'An error occurred while deleting the menu item.' . mysqli_error($conn);
    }

    header('Location: update_menu.php');
    exit;
}

// Handle new item addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_name']) && isset($_POST['new_description']) && isset($_POST['new_price'])) {
    $menuId = $_POST['menu_id'];
    $newName = $_POST['new_name'];
    $newDescription = $_POST['new_description'];
    $newPrice = $_POST['new_price'];


    // Validate the input values (you can add more validation here)
    if (empty($newName) || empty($newDescription) || !is_numeric($newPrice) || $newPrice <= 0) {
        $_SESSION['error_message'] = 'Invalid input values. Please provide valid values.';
    } else {
        // Add the new menu item to the database
        $insertQuery = "INSERT INTO menu_item (menu_id, name, description, price) VALUES ('$menuId','$newName', '$newDescription', '$newPrice')";
        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            $_SESSION['success_message'] = 'Menu item added successfully.';
        } else {
            $_SESSION['error_message'] = 'An error occurred while adding the menu item.' . mysqli_error($conn);
        }
    }

    header('Location: update_menu.php');
    exit;
}

// Fetch all menu items from the database
$menuItems = array();
$query = "SELECT * FROM menu_item";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $menuItems[] = $row;
    }
} else {
    $_SESSION['error_message'] = 'Error fetching menu items: ' . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safari Flavors - Update Menu</title>
 <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        header {
            background-color: #4CAF50;
            padding: 20px;
            text-align: center;
            color: white;
        }

        h2{
            text-align: center;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }

        .edit-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .edit-form label {
            font-weight: bold;
        }

        .edit-form input[type="text"],
        .edit-form textarea,
        .edit-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .edit-form input[type="number"] {
            width: 150px;
        }

        .edit-form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }

        .edit-form input[type="submit"]:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 10px;
            color: #333;
            font-weight: bold;
        }

        .success-message {
            color: #009900;
        }

        .error-message {
            color: #ff0000;
        }

        ul.menu-list {
            list-style: none;
            padding: 0;
        }

        ul.menu-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        ul.menu-list li form {
            margin: 0;
        }

        ul.menu-list li button {
            background-color: #ff3333;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
      table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .edit-button {
            background-color: #4287f5;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .edit-button:hover {
            background-color: #3470cd;
        }

        td:nth-child(odd){
            /* background-color: #000; */

        }

    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>
    <h2>Update Menu</h2>
    <!-- Display success or error messages if applicable -->
 <?php
        if (isset($_SESSION['success_message'])) {
            echo '<p class="message success-message">' . $_SESSION['success_message'] . '</p>';
            unset($_SESSION['success_message']);
        }
        if (isset($_SESSION['error_message'])) {
            echo '<p class="message error-message">' . $_SESSION['error_message'] . '</p>';
            unset($_SESSION['error_message']);
        }
        ?>
    <h3>Add New Item</h3>
    <div class="edit-form">
        <form method="POST" action="update_menu.php">
            <label for="new_name">Name:</label>
            <input type="text" name="new_name" required><br>
            <label for="new_description">Description:</label>
            <textarea name="new_description" rows="4" required></textarea><br>
            <label for="new_price">Price:</label>
            <input type="number" name="new_price" step="0.01" required><br>
            <label for="menu_id">Menu:</label>
            <select name="menu_id">
            <?php
            // Fetch all menus from the database and populate the dropdown
            $menuQuery = "SELECT * FROM menu";
            $menuResult = mysqli_query($conn, $menuQuery);
            
            if ($menuResult) {
                while ($menuRow = mysqli_fetch_assoc($menuResult)) {
                    echo '<option value="' . $menuRow['menu_id'] . '">' . $menuRow['name'] . '</option>';
                }
            }
            ?>
        </select><br>
            <input type="submit" value="Add Item">
        </form>
    </div>
      <h3>Menu Items</h3>
         <table>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($menuItems as $menuItem): ?>
                <tr>
                    <td><?php echo $menuItem['name']; ?></td>
                    <td><?php echo $menuItem['description']; ?></td>
                    <td><?php echo $menuItem['price']; ?></td>
                    <td class="actions">
                        <form method="POST" action="update_menu.php">
                            <input type="hidden" name="delete_item_id" value="<?php echo $menuItem['item_id']; ?>">
                            <button type="submit" class="delete-button">Delete</button>
                        </form>
                        <a href="edit_item.php?item_id=<?php echo $menuItem['item_id']; ?>" class="edit-button">Edit</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>