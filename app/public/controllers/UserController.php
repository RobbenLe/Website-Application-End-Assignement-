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
    $user = $this->getUserByUsername($username);

    if ($user && password_verify($password, $user["password"])) {
        return [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
    } else {
        throw new Exception("Invalid username or password.");
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

        // Authenticate the user using the UserModel directly
        $user = $this->userModel->login($username, $password);

        // Start a session if login is successful
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Store user details in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['is_logged_in'] = true;

          // Role-based redirection
          switch ($user['role']) {
            case 'customer':
                header("Location: /homePage");
                break;
            case 'technician':
                header("Location: /TechnicianDashboard");
                break;
            case 'admin':
                header("Location: /AdminDashboard");
                break;
            default:
                header("Location: /LoginPage");
                break;
            }
            exit();
    } catch (Exception $e) {
        // Handle errors and redirect back with an error message
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['login_error'] = $e->getMessage();
        header("Location: /LoginPage");
        exit();
    }
  }

}