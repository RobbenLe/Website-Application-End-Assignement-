<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Rosa Nails & Spa</title>
  <link rel="stylesheet" href="../../assets/css/register.css">
</head>
<body>
  <div class="register-container">
    <h1 class="register-title">Create an Account</h1>
    <p class="register-subtitle">Join us for a luxurious experience at Rosa Nails & Spa</p>
    <form class="register-form">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Enter your full name" required>
      </div>
      
      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
      </div>
      
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
      </div>
      
      <div class="form-group">
        <label for="confirm-password">Confirm Password</label>
        <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm your password" required>
      </div>
      
      <button type="submit" class="register-button">Register</button>
    </form>
    <p class="login-link">
      Already have an account? <a href="/LoginPage">Login here</a>
    </p>
  </div>
</body>
</html>