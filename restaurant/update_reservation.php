<?php
session_start();
include 'connection.php';

// Check if the user is logged in and is a manager or staff
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error_message'] = 'Please log in to update the reservation status.';
    header('Location: login.php');
    exit;
}

$userID = $_SESSION['user_id'];

// Check if the user role is manager or staff
$roleQuery = "SELECT role FROM users WHERE user_id = $userID";
$roleResult = mysqli_query($conn, $roleQuery);
$userRole = mysqli_fetch_assoc($roleResult)['role'];

if ($userRole !== 'manager' && $userRole !== 'staff') {
    $_SESSION['error_message'] = 'You are not authorized to update the reservation status.';
    header('Location: reservations.php');
    exit;
}

// Handle the form submission for updating the reservation status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id']) && isset($_POST['new_status'])) {
    $reservationID = $_POST['reservation_id'];
    $newStatus = $_POST['new_status'];

    // Update the reservation status in the database
    $updateQuery = "UPDATE reservations SET reservation_status = '$newStatus' WHERE reservation_id = $reservationID";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        $_SESSION['success_message'] = 'Reservation status updated successfully.';
    } else {
        $_SESSION['error_message'] = 'An error occurred while updating the reservation status. Please try again later.';
    }

    header('Location: reservations.php');
    exit;
}
?>
