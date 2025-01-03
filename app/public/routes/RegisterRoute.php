<?php
require_once(__DIR__ . "/../controllers/UserController.php");

Route::add('/Register', function () {
    $userController = new UserController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Input Validation
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $role = isset($_POST['role']) ? trim($_POST['role']) : 'customer';

            // Ensure fields are not empty
            if (empty($email) || empty($username) || empty($password)) {
                throw new Exception("All fields (email, username, password) are required.");
            }

            // Call the register method in the UserController
            $message = $userController->register($email, $username, $password, $role);

            // On success, redirect or display success message
            $_SESSION['success_message'] = $message;
            header("Location: /RegisterSuccess");
            exit();
        } catch (Exception $e) {
            // Store error in session and reload page
            $_SESSION['register_error'] = $e->getMessage();
            header("Location: /Register");
            exit();
        }
    }

    // Load the registration form view
    require_once(__DIR__ . "/../views/pages/Register.php");
}, ["get", "post"]);