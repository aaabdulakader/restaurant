

<!-- Databse Connection -->
<?php
    // Connect to the database
    $servername = 'localhost';
    $username = 'root';
    $password = '';
    $dbname = 'restaurant_db';

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the database connection
    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    
?>


