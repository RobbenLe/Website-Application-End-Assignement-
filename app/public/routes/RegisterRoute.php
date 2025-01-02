<?php // this is my login routes
require_once(__DIR__ . "/../controllers/UserController.php");

Route::add('/Register', function(){
    require_once(__DIR__ . "/../views/pages/Register.php");
});