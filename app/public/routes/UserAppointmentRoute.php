<?php
require_once(__DIR__ . "/../lib/SessionHelper.php");

// Define the route for user appointments
Route::add('/userAppointment', function() {

    // Ensure the user is logged in
    requireLogin();

    // Check if the user is a customer
    if ($_SESSION['role'] !== 'customer') {
        header("Location: /LoginPage");
        exit();
    }

    // Create an instance of UserController
    $userController = new UserController();

    // Fetch appointments for the logged-in user
    $appointments = $userController->getAppointments();

    // Pass the appointments data to the UserAppointment view
    require_once(__DIR__ . "/../views/pages/UserAppointment.php");
}, 'get');
