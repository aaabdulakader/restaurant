<?php 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Safari Flavors</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f8f8f8;
        }

        header {
            background-color: #333;
            color:#333;
            padding: 10px;
            text-align: center;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .hero {
            background-image: url('path/to/your/hero-image.jpg');
            background-size: cover;
            height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .hero h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 24px;
            margin-bottom: 40px;
        }

        .cta-button {
            background-color: #ffcc00;
            color: #fff;
            border: none;
            padding: 14px 32px;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .cta-button:hover {
            background-color: #ffa500;
        }

        .login-link {
            color: #ffcc00;
            text-decoration: underline;
        }

        .section {
            background-color: #fff;
            padding: 40px;
            margin-bottom: 40px;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .section h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .section p {
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .section .button-container {
            text-align: center;
        }

        .section .button-container a {
            background-color: #ffcc00;
            color: #fff;
            border: none;
            padding: 14px 32px;
            font-size: 20px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .section .button-container a:hover {
            background-color: #ffa500;
        }
    </style>
</head>
<body>
  <?php include 'navbar.php'; ?>

    <div class="hero">
        <div class="hero-content">
            <h1>Welcome to Safari Flavors!</h1>
            <?php if (isset($_SESSION['user_id'])): ?>
                <p>Welcome, <?php echo $_SESSION['full_name']; ?>!</p>
                <a href="menu.php" class="cta-button">Explore Our Flavors</a>
            <?php else: ?>
                <p>Please <a class="login-link" href="login.php">Login</a> to make orders!</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="container">
        <div class="section">
            <h2>About Us</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            <div class="button-container">
                <a href="about.php">Learn More</a>
            </div>
        </div>

        <div class="section">
            <h2>Contact Us</h2>
            <p>If you have any questions or inquiries, please feel free to get in touch with us.</p>
            <div class="button-container">
                <a href="contact.php">Contact Us</a>
            </div>
        </div>
    </div>
</body>
<?php include 'footer.php'; ?>
</html>
