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

Route::add('/cancelAppointment', function() {
    // Ensure the user is logged in
    requireLogin();

    // Check if the user is a customer
    if ($_SESSION['role'] !== 'customer') {
        header("Location: /LoginPage");
        exit();
    }

    // Get the appointment id from the POST data
    if (isset($_POST['appointment_id'])) {
        $appointmentId = $_POST['appointment_id'];
        
        // Create an instance of UserController
        $userController = new UserController();
        
        // Call cancelAppointment method
        $result = $userController->cancelAppointment($appointmentId);
        
        // Return success or failure message
        $appointments = $userController->getAppointments(); // Refresh appointments
        require_once(__DIR__ . "/../views/pages/UserAppointment.php");
    } else {
        header("Location: /userAppointment");
        exit();
    }
}, 'post');
