<?php
require_once(__DIR__ . "/../controllers/ServiceController.php");

Route::add('/BookingTreatments', function () {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $serviceController = new ServiceController();
    $services = $serviceController->getAllServices();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['selected_treatments'])) {
            $_SESSION['selected_treatments'] = $_POST['selected_treatments'];
            $_SESSION['total_duration'] = array_sum(array_column($_POST['selected_treatments'], 'duration'));
            header("Location: /ChooseTimePage");
            exit();
        }
    }

    require_once(__DIR__ . "/../views/pages/BookingTreatments.php");
}, ["get", "post"]);
