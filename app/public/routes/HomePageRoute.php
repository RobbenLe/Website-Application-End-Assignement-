<?php
require_once(__DIR__ . "/../lib/SessionHelper.php");

Route::add('/homePage' ,function(){
    requireLogin(); // Ensure user is logged in

    if ($_SESSION['role'] !== 'customer') {
        header("Location: /Unauthorized");
        exit();
    }

    // Pass user data to the view
    $userId = $_SESSION['user_id'];

require_once(__DIR__ . "/../views/pages/homePage.php");
});