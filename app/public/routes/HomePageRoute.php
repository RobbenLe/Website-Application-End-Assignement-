<?php
require_once(__DIR__ . "/../lib/SessionHelper.php");

Route::add('/homePage' ,function(){
    requireLogin(); // Ensure user is logged in

    if ($_SESSION['role'] !== 'customer') {
        header("Location: /Unauthorized");
        exit();
    }

require_once(__DIR__ . "/../views/pages/homePage.php");
});