<?php

require_once(__DIR__ . "/../models/UserModel.php");

class UserController{
  private $userModel;

  public function __construct()
  {
      $this->userModel = new UserModel();
  }

  public function processLogin($username, $password)
  {
      // Fetch user data
      $user = $this->userModel->getUserByUsername($username);

      if ($user) {
          // Verify the hashed password
          if (password_verify($password, $user['password'])) {
              // Login successful
              session_start();
              $_SESSION['username'] = $user['username'];
              $_SESSION['role'] = $user['role'];

              echo "Login successful!<br>";
              echo "Welcome, " . htmlspecialchars($user['username']) . " (Role: " . htmlspecialchars($user['role']) . ")";
              // Redirect to a dashboard or home page
              header("Location: /dashboard");
              exit;
          } else {
              // Incorrect password
              echo "Invalid password. Please try again.";
          }
      } else {
          // User not found
          echo "No user found with that username.";
      }
  }
}