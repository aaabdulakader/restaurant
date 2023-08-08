<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us - Safari Flavors</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Navbar styles */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 999;
        }

        nav ul {
            list-style-type: none;
            display: flex;
            gap: 20px;
        }

        nav ul li {
            margin-right: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }

        nav ul li a:hover {
            color: #ffcc00;
        }

        a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }

        /* Content styles */
        body {
            padding-top: 60px; /* Adjust this value to give space for the fixed navbar */
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }

        .container h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .container p {
            font-size: 18px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .container .contact-form {
            display: flex;
            flex-direction: column;
        }

        .container .contact-form input,
        .container .contact-form textarea {
            margin-bottom: 20px;
            padding: 10px;
            font-size: 16px;
        }

        .container .contact-form input[type="submit"] {
            background-color: #ffcc00;
            color: #fff;
            border: none;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .container .contact-form input[type="submit"]:hover {
            background-color: #ffa500;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <h2>Contact Us</h2>
        <p>If you have any questions or inquiries, please feel free to get in touch with us using the form below.</p>

        <form class="contact-form" action="process_contact.php" method="POST">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <input type="submit" value="Send Message">
        </form>
    </div>
</body>
</html>
