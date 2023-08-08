
    <?php
session_start();
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect back to the login page with an error message
    $_SESSION['error_message'] = 'Please log in to view reservations.';
    header('Location: login.php');
    exit;
}

// Get the user ID and role
$userID = $_SESSION['user_id'];

// Retrieve the user's role from the database
$roleQuery = "SELECT role FROM users WHERE user_id = $userID";
$roleResult = mysqli_query($conn, $roleQuery);
$userRole = mysqli_fetch_assoc($roleResult)['role'];

// Initialize an empty array to store reservations
$reservations = array();

// Fetch reservations based on user role
if ($userRole === 'manager' || $userRole === 'staff') {
    // Fetch all reservations for managers and staff
    $reservationsQuery = "SELECT * FROM reservations";
} else {
    // Fetch reservations for the logged-in customer
    $reservationsQuery = "SELECT * FROM reservations WHERE user_id = $userID";
}
$reservationsResult = mysqli_query($conn, $reservationsQuery);

// Fetch all reservations for managers and staff
if ($userRole === 'manager' || $userRole === 'staff') {
    while ($row = mysqli_fetch_assoc($reservationsResult)) {
        $reservations[] = $row;
    }
} else {
    // Fetch user-specific reservations
    if (mysqli_num_rows($reservationsResult) > 0) {
        while ($row = mysqli_fetch_assoc($reservationsResult)) {
            $reservations[] = $row;
        }
    } else {
        $noReservations = true; // Set a flag indicating no reservations
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safari Flavors - Reservations</title>
    <style>
    
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

        .reservation-table {
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

        .reserve-button {
            text-align: center;
            padding: 20px;
        }

        .reserve-button a {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }

        .reserve-button a:hover {
            background-color: #45a049;
        }

        /* Style the select dropdown for managers and staff */
        select {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
        }

        .reservation-table p{
            text-align: center;
            padding: 20px;
        }

        /* Style the reserve button for customers */
        .reserve-button a {
            padding: 12px 24px;
            background-color: #4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .reserve-button a:hover {
            background-color: #45a049;
        }    
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <h2>Reservations</h2>
    <div class="reservation-table">
        <?php if (isset($noReservations) ) { ?>
            <p>No reservations found.</p>
        <?php } else { ?>
        <table>
            
            <tr>
                <th>Reservation ID</th>
                <th>Customer ID</th>
                <th>Date</th>
                <th>Time</th>
                <th>Number of Guests</th>
                <th>Status</th>
                <?php if ($userRole === 'manager' || $userRole === 'staff') { ?>
                    <th>Action</th>
                <?php } ?>
            </tr>
            <?php
            // Loop through the reservations and display them in the table
            foreach ($reservations as $reservation) {
                $reservationID = $reservation['reservation_id'];
                $customerID = $reservation['user_id'];
                $reservationDate = $reservation['reservation_date'];
                $reservationTime = $reservation['time'];
                $numGuests = $reservation['num_guests'];
                $reservationStatus = $reservation['reservation_status'];

                echo '<tr>';
                echo '<td>' . $reservationID . '</td>';
                echo '<td>' . $customerID . '</td>';
                echo '<td>' . $reservationDate . '</td>';
                echo '<td>' . $reservationTime . '</td>';
                echo '<td>' . $numGuests . '</td>';
                echo '<td>' . $reservationStatus . '</td>';
                if ($userRole === 'manager' || $userRole === 'staff') {
                    echo '<td>';
                    // Show reservation status dropdown for managers and staff
                    echo '<form action="update_reservation.php" method="post">';
                    echo '<input type="hidden" name="reservation_id" value="' . $reservationID . '">';
                    echo '<select name="new_status" onchange="this.form.submit()">';
                    echo '<option value="Pending" ' . ($reservationStatus === 'Pending' ? 'selected' : '') . '>Pending</option>';
                    echo '<option value="Confirmed" ' . ($reservationStatus === 'Confirmed' ? 'selected' : '') . '>Confirmed</option>';
                    echo '<option value="Cancelled" ' . ($reservationStatus === 'Cancelled' ? 'selected' : '') . '>Cancelled</option>';
                    echo '</select>';
                    echo '</form>';
                    echo '</td>';
                }
                echo '</tr>';
            }
            ?>
        </table>
         <?php } ?>
    </div>


        <div class="reserve-button">
            <a href="reserve.php">Reserve</a>
        </div>

</body>
</html>
