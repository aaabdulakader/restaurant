<?php
session_start();
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect back to the login page with an error message
    $_SESSION['error_message'] = 'Please log in to view your orders.';
    header('Location: login.php');
    exit;
}

// Get the user ID and role
$userID = $_SESSION['user_id'];

// Retrieve the user's role from the database
$roleQuery = "SELECT role FROM users WHERE user_id = $userID";
$roleResult = mysqli_query($conn, $roleQuery);
$userRole = mysqli_fetch_assoc($roleResult)['role'];

// Initialize an empty variable to store the orders
$orders = array();

if ($userRole === 'manager' || $userRole === 'staff') {
    // For managers or staff, fetch all orders
    $allOrdersQuery = "SELECT * FROM orders";
    $allOrdersResult = mysqli_query($conn, $allOrdersQuery);

    // Check if there are any orders
    if (mysqli_num_rows($allOrdersResult) > 0) {
        // Loop through the orders and store them in the $orders array
        while ($row = mysqli_fetch_assoc($allOrdersResult)) {
            $orders[] = $row;
        }
    }
} else {
    // For regular users, fetch their orders only
    $userOrdersQuery = "SELECT * FROM orders WHERE user_id = $userID";
    $userOrdersResult = mysqli_query($conn, $userOrdersQuery);

    // Check if there are any orders
    if (mysqli_num_rows($userOrdersResult) > 0) {
        // Loop through the orders and store them in the $orders array
        while ($row = mysqli_fetch_assoc($userOrdersResult)) {
            $orders[] = $row;
        }
    }
}

// Function to update the order status
function updateOrderStatus($orderID, $newStatus) {
    global $conn;
    $updateQuery = "UPDATE orders SET order_status = '$newStatus' WHERE order_id = $orderID";
    return mysqli_query($conn, $updateQuery);
}

// Handle the form submission for updating the order status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $orderID = $_POST['order_id'];
    $newStatus = $_POST['new_status'];

    // Check if the user is authorized to update the order status (only managers and staff are allowed)
    if ($userRole === 'manager' || $userRole === 'staff') {
        // Update the order status in the database
        if (updateOrderStatus($orderID, $newStatus)) {
            // Success! Redirect back to the same page with a success message
            $_SESSION['success_message'] = 'Order status updated successfully.';
            header('Location: orders.php');
            exit;
        } else {
            // If there was an error updating the order status, redirect back to the same page with an error message
            $_SESSION['error_message'] = 'An error occurred while updating the order status. Please try again later.';
            header('Location: orders.php');
            exit;
        }
    } else {
        // User is not authorized to update the order status, redirect back to the same page with an error message
        $_SESSION['error_message'] = 'You are not authorized to update the order status.';
        header('Location: orders.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safari Flavors - Orders</title>
    <style>
        /* Your CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            padding: 20px 0;
            background-color: #f8f8f8;
        }

        .order-table {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f8f8f8;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .order-status-select {
            display: inline-block;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            background-color: #f9f9f9;
        }

        .order-status-select select {
            border: none;
            background-color: transparent;
            font-size: 14px;
            cursor: pointer;
        }

        .order-status-select select option {
            background-color: #fff;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <h2>Orders</h2>
    <div class="order-table">
        <table>
            <tr>
                <th>Order ID</th>
                <th>Order Date</th>
                <th>Customer Name</th>
                <th>Total Cost</th>
                <th>Delivery Option</th>
                <th>Status</th>
              
            </tr>
            <?php
            // Loop through the orders and display them in the table
            foreach ($orders as $order) {
                $orderID = $order['order_id'];
                $orderDate = $order['order_date'];
                $totalCost = $order['total_cost'];
                $deliveryOption = $order['delivery_option'];
                $pickupTime = $order['pickup_time'];
                $deliveryAddress = $order['delivery_address'];
                $paymentInfo = $order['payment_info'];
                $orderStatus = $order['order_status'];

                // Fetch the customer name for this order
                $customerID = $order['user_id'];
                $customerNameQuery = "SELECT full_name FROM users WHERE user_id = $customerID";
                $customerNameResult = mysqli_query($conn, $customerNameQuery);
                $customerName = mysqli_fetch_assoc($customerNameResult)['full_name'];

                echo '<tr>';
                echo '<td>' . $orderID . '</td>';
                echo '<td>' . $orderDate . '</td>';
                echo '<td>' . $customerName . '</td>';
                echo '<td>$' . $totalCost . '</td>';
                echo '<td>' . $deliveryOption . '</td>';
               
                if ($userRole === 'manager' || $userRole === 'staff') {
                    echo '<td>';
                    // Show order status dropdown for managers and staff
                    echo '<div class="order-status-select">';
                    echo '<form action="orders.php" method="post">';
                    echo '<input type="hidden" name="order_id" value="' . $orderID . '">';
                    echo '<select name="new_status" onchange="this.form.submit()">';
                    echo '<option value="pending" ' . ($orderStatus === 'pending' ? 'selected' : '') . '>Pending</option>';
                    echo '<option value="processing" ' . ($orderStatus === 'processing' ? 'selected' : '') . '>Processing</option>';
                    echo '<option value="completed" ' . ($orderStatus === 'completed' ? 'selected' : '') . '>Completed</option>';
                    echo '<option value="cancelled" ' . ($orderStatus === 'cancelled' ? 'selected' : '') . '>Cancelled</option>';
                    echo '</select>';
                    echo '</form>';
                    echo '</div>';
                    echo '</td>';
                }else {
                    // Show order status text for customers
                    echo '<td>' . $orderStatus . '</td>';
                }

                echo '</tr>';
            }
            ?>
        </table>
    </div>
</body>
</html>
