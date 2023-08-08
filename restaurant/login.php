<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Safari Flavors - Login</title>
    <style>
        /* Styles for the login form */
        .login-container {
            width: 300px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
        }
        
        .login-container h2 {
            text-align: center;
        }
        
        .login-container label,
        .login-container input {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .login-container input[type="submit"] {
            background-color: #ffcc00;
            color: #fff;
            border: none;
            padding: 5px 10px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
        }
        
        .login-container input[type="submit"]:hover {
            background-color: #ffa500;
        }
        
        .login-container .signup-link {
            text-align: center;
            margin-top: 10px;
            color black;
        }
        .signup-lin a {
            color: #ffcc00;
            /* z-index: 999; */
            font-size: 18px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="login-container">
        <h2>Login</h2>
        <form action="login_process.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" value="Login">
        </form>
        
        <p class="signup-link">
            <a href="register.php">Don't have an account? Sign up</a>
        </p>
    </div>
</body>
</html>
