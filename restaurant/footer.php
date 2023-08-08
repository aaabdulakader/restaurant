<style>
    footer {
        background-color: #333;
        color: #fff;
        padding: 40px 0;
    }

    .footer-container {
        display: flex;
        justify-content: space-between;
        max-width: 1200px;
        margin: 0 auto;
    }

    .footer-links ul {
        list-style: none;
        padding: 0;
    }

    .footer-links h4 {
        margin-bottom: 20px;
    }

    .footer-links a {
        color: #fff;
        text-decoration: none;
        display: block;
        margin-bottom: 8px;
        transition: color 0.3s;
    }

    .footer-links a:hover {
        color: #ffcc00;
    }

    .footer-social ul {
        list-style: none;
        padding: 0;
    }

    .footer-social a {
        color: #fff;
        text-decoration: none;
        display: block;
        margin-bottom: 8px;
        transition: color 0.3s;
    }

    .footer-social a:hover {
        color: #ffcc00;
    }

    .footer-bottom {
        text-align: center;
        margin-top: 20px;
    }
</style>
<footer>
    <div class="footer-container">
        <div class="footer-links">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="reservations.php">Reservations</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>

            </ul>
        </div>
        <div class="footer-links">
            <h4>Information</h4>
            <ul>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms of Use</a></li>
                <li><a href="#">FAQ</a></li>
            </ul>
        </div>
        <div class="footer-social">
            <h4>Follow Us</h4>
            <ul>
                <li><a href="#"><i class="fab fa-facebook"></i> Facebook</a></li>
                <li><a href="#"><i class="fab fa-twitter"></i> Twitter</a></li>
                <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> Safari Flavors. All rights reserved.</p>
    </div>
</footer>
