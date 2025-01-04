<?php // this is my login routes
require_once(__DIR__ . "/../controllers/UserController.php");

Route::add('/LoginPage', function () {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $userController = new UserController();
            $userController->processLogin($_POST['username'], $_POST['password']);
        } catch (Exception $e) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['login_error'] = $e->getMessage();
            header('Location: /LoginPage');
            exit();
        }
    } else {
        // Show the login page for GET requests
        require(__DIR__ . "/../views/pages/LoginPage.php");
    }
}, ["get", "post"]);

