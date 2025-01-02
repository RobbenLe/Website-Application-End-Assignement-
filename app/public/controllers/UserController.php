<?php

require_once(__DIR__ . "/../models/UserModel.php");

class UserController{
  private $userModel;

  public function __construct()
  {
      $this->userModel = new UserModel();
  }

  public function getUserByUsername($username) 
  {
    return $this->userModel->getUserByUsername($username);
  }

  public function register($email, $username, $password, $role = 'customer') 
  {
    try {
        $this->userModel->register($email, $username, $password, $role);
        return "User successfully registered!";
    } catch (Exception $e) {
        return "Registration failed: " . $e->getMessage();
    }
  }

  public function login($username, $password) 
  {
    try {
        // Validate user credentials using the model's login method
        $user = $this->userModel->login($username, $password);
        
        // Start a session if login is successful
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Store user details in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['is_logged_in'] = true;

        return "Login successful! Welcome, " . $user['username'];
    } catch (Exception $e) {
        // Handle login failure
        return "Login failed: " . $e->getMessage();
    }
  }

  public function getUserById($id) 
  {
    try {
        // Fetch the user by ID from the UserModel
        $user = $this->userModel->getUserById($id);

        // Check if user was found
        if ($user) {
            return $user; // Return the user details as an associative array
        } else {
            throw new Exception("User not found with ID: $id");
        }
    } catch (Exception $e) {
        // Handle errors gracefully
        return "Error: " . $e->getMessage();
    }
  }

  public function processLogin($username, $password)
  {
      try {
          // Validate inputs
          if (empty($username) || empty($password)) {
              throw new Exception("Username and password are required.");
          }
  
          // Authenticate the user
          $user = $this->login($username, $password);
  
          // Redirect on successful login
          header("Location: /Dashboard");
          exit();
      } catch (Exception $e) {
          // Handle errors and redirect back with an error message
          $_SESSION['login_error'] = $e->getMessage();
          header("Location: /LoginPage");
          exit();
      }
  }

}