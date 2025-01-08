<?php // this is my login routes
require_once(__DIR__ . "/../controllers/UserController.php");

Route::add('/LoginPage', function () {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');

        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $username = $data['username'] ?? null;
            $password = $data['password'] ?? null;

            if (!$username || !$password) {
                echo json_encode(["error" => "Username and password are required."]);
                http_response_code(400);
                exit();
            }

            $userController = new UserController();
            $user = $userController->login($username, $password);

            if ($user) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['is_logged_in'] = true;

                echo json_encode([
                    "success" => true,
                    "user" => [
                        "id" => $user['id'],
                        "username" => $user['username'],
                        "role" => $user['role']
                    ]
                ]);
            } else {
                throw new Exception("Invalid username or password.");
            }
        } catch (Exception $e) {
            echo json_encode(["error" => $e->getMessage()]);
            http_response_code(401);
        }
        exit();
    } else {
        require(__DIR__ . "/../views/pages/LoginPage.php");
    }
}, ["get", "post"]);


