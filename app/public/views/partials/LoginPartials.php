<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="../../assets/css/Login.css"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600&display=swap">
    <title>Login Page</title>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>
        
        <!-- Display Error Message -->
        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="error-message">
                <?php 
                    echo htmlspecialchars($_SESSION['login_error']); 
                    unset($_SESSION['login_error']); // Clear the error after displaying it
                ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="/LoginPage" method="post">
            <div class="input-group">
                <label for="username">User Name</label>
                <input type="text" id="username" name="username" required />
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required />
            </div>
            
            <button type="submit" class="login-btn">Login</button>
        </form>
        
        <p class="register-text">Don't have an account? <a href="/Register">Register</a></p>
    </div>

    <!-- Link to external JavaScript file -->
    <script src="../../assets/js/login.js"></script>
</body>
</html>
