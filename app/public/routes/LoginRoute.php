<?php
require_once(__DIR__ . "/../controllers/UserController.php");

Route::add('/LoginPage', function () {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');

        try {
            // Get JSON input
            $data = json_decode(file_get_contents("php://input"), true);
            $username = $data['username'] ?? null;
            $password = $data['password'] ?? null;

            // Validate input fields
            if (!$username || !$password) {
                http_response_code(400);
                echo json_encode(["success" => false, "error" => "Username and password are required."]);
                exit();
            }

            $userController = new UserController();
            $user = $userController->getUserByUsername($username); // Separate function to check if user exists

            // Check if username exists
            if (!$user) {
                http_response_code(401);
                echo json_encode(["success" => false, "error" => "Username does not exist."]);
                exit();
            }

            // Validate password
            if (!password_verify($password, $user['password'])) { // Ensure passwords are hashed
                http_response_code(401);
                echo json_encode(["success" => false, "error" => "Incorrect password."]);
                exit();
            }

            // Start session if not already started
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Store user session details
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['is_logged_in'] = true;

            // Send success response
            echo json_encode([
                "success" => true,
                "user" => [
                    "id" => $user['id'],
                    "username" => $user['username'],
                    "role" => $user['role']
                ]
            ]);
            exit();
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "error" => "An unexpected error occurred."]);
            exit();
        }
    } else {
        require(__DIR__ . "/../views/pages/LoginPage.php");
    }
}, ["get", "post"]);



