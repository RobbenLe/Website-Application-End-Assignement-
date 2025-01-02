<?php // this is my login routes
require_once(__DIR__ . "/../controllers/UserController.php");

Route::add('/LoginPage', function () {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userController = new UserController();
        $userController->processLogin($_POST['username'], $_POST['password']);
    } else {
        require(__DIR__ . "/../views/pages/LoginPage.php");
    }
}, ["get", "post"]);

