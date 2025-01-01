<?php // this is my login routes
Route::add('/LoginPage', function () {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userController = new UserController();
        $userController->processLogin($_POST['username'], $_POST['password']);
    } else {
        require(__DIR__ . "/../views/pages/LoginPage.php");
    }
}, ["get", "post"]);

