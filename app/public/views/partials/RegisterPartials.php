<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="/assets/css/register.css?v=1.0.2">
</head>
<body>
    <div class="register-container">
        <h1>Register</h1>

        <?php if (isset($_SESSION['register_error'])): ?>
            <div class="error-message">
                <?php 
                    echo htmlspecialchars($_SESSION['register_error']);
                    unset($_SESSION['register_error']); 
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message">
                <?php 
                    echo htmlspecialchars($_SESSION['success_message']);
                    unset($_SESSION['success_message']); 
                ?>
            </div>
        <?php endif; ?>

        <form action="/Register" method="POST">
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="input-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="input-group">
                <label for="role">Role</label>
                <select id="role" name="role">
                    <option value="customer">Customer</option>
                    <option value="technician">Technician</option>
                </select>
            </div>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>