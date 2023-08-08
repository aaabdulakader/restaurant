

    <?php
session_start();



include 'connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If the user is not logged in, redirect back to the login page with an error message
    $_SESSION['error_message'] = 'Please log in to make a reservation.';
    header('Location: login.php');
    exit;
}

// Get the user ID
$userID = $_SESSION['user_id'];

// Handle the form submission for making a reservation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_date']) && isset($_POST['time']) && isset($_POST['num_guests'])) {
    $reservationDate = $_POST['reservation_date'];
    $reservationTime = $_POST['time'];
    $numGuests = intval($_POST['num_guests']);

    // Validate the reservation date, time, and number of guests
    $today = date('Y-m-d');
    $minReservationDate = date('Y-m-d', strtotime('+1 day')); // At least one day in advance
    $maxGuests = 10;

    if ($reservationDate < $minReservationDate) {
        $_SESSION['error_message'] = 'Please select a reservation date at least one day in advance.';
    } elseif ($numGuests < 1 || $numGuests > $maxGuests) {
        $_SESSION['error_message'] = 'Number of guests should be between 1 and ' . $maxGuests . '.';
    } else {
        // Insert the reservation details into the database
        $insertQuery = "INSERT INTO reservations (user_id, reservation_date, time, num_guests) VALUES ('$userID', '$reservationDate', '$reservationTime', $numGuests)";
        $insertResult = mysqli_query($conn, $insertQuery);

        if ($insertResult) {
            $_SESSION['success_message'] = 'Reservation successfully created.';
        } else {
            $_SESSION['error_message'] = 'An error occurred while making the reservation. Please try again later. MySQL Error: ' . mysqli_error($conn);
        }
    }

    header('Location: reserve.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safari Flavors - Make a Reservation</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

       

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .reservation-form {
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .reservation-form label {
            display: block;
            margin-bottom: 10px;
        }

        .reservation-form input[type="date"],
        .reservation-form input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .reservation-form button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        .success-message {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php';?>

    <div class="container">
        <h2>Make a Reservation</h2>
        <div class="reservation-form">
            <?php
            // Display error messages, if any
            if (isset($_SESSION['error_message'])) {
                echo '<p class="error-message">' . $_SESSION['error_message'] . '</p>';
                unset($_SESSION['error_message']);
            }

            // Display success messages, if any
            if (isset($_SESSION['success_message'])) {
                echo '<p class="success-message">' . $_SESSION['success_message'] . '</p>';
                unset($_SESSION['success_message']);
            }
            ?>
            <form method="post" action="reserve.php">
                <label for="reservation_date">Reservation Date:</label>
                <input type="date" name="reservation_date" id="reservation_date" required>

                <label for="time">Reservation Time:</label>
                <input type="time" name="time" id="time" required>

                <label for="num_guests">Number of Guests:</label>
                <input type="number" name="num_guests" id="num_guests" min="1" required>

                <button type="submit">Submit Reservation</button>
            </form>
        </div>
    </div>
</body>
</html>
