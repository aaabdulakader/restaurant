<?php
// Check the current page being requested
$page = $_GET['page'] ?? '';

// Display content based on the requested page
// switch ($page) {
//     case 'home':
//         // Home page content
//         echo '<h1>Welcome to Safari Flavors!</h1>';
//         echo '<p>This is the homepage of Safari Flavors.</p>';
//         break;

//     case 'menu':
//         // Menu page content
//         echo '<h1>Our Menu</h1>';
//         echo '<p>Check out our delicious menu items.</p>';
//         include 'menu.php';
//         break;

//     case 'about':
//         // About page content
//         echo '<h1>About Safari Flavors</h1>';
//         echo '<p>Learn more about our restaurant and its history.</p>';
//         // Add more content specific to the about page
//         break;

//     default:
//         // Default content for other pages or when no page is specified
//         echo '<h1>Welcome to Safari Flavors!</h1>';
//         echo '<p>Explore our website using the navigation menu.</p>';
//         break;
// }
?>
