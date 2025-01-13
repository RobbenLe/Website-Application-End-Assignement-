<?php
require_once(__DIR__ . "/../controllers/TechnicianController.php"); 

Route::add('/TechnicianDashBoardPage', function () {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Ensure the user is logged in and has a valid role
    if ($_SESSION['role'] !== 'technician') {
        header("Location: /Unauthorized");
        exit();
    }

    // Include the Technician Dashboard HTML file
    require_once(__DIR__ . "/../views/pages/TechnicianDashBoardPage.php");
});



Route::add('/getAppointmentsForTechnician', function () {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $technicianId = $_SESSION['user_id']; // Assuming technician is logged in and ID is in session
    $date = $_GET['date'] ?? null;

    if (!$date) {
        echo json_encode(["error" => "Date is required."]);
        http_response_code(400);
        exit();
    }

    try {
        $technicianController = new TechnicianController();
        $appointments = $technicianController->getAppointmentsByDate($technicianId, $date);

        echo json_encode($appointments);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
    exit();
});

Route::add('/SetAvailability', function () {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Get input values
            $availableDate = $_POST['available_date'] ?? null;
            $startTime = $_POST['start_time'] ?? null;
            $endTime = $_POST['end_time'] ?? null;
            $technicianId = $_SESSION['user_id'] ?? null;

            // Validate input
            if (!$technicianId) {
                throw new Exception("Technician is not logged in.");
            }
            if (empty($availableDate) || empty($startTime) || empty($endTime)) {
                throw new Exception("All fields are required.");
            }

            // Save availability
            $technicianController = new TechnicianController();
            $success = $technicianController->setAvailability($technicianId, $availableDate, $startTime, $endTime);

            if ($success) {
                $_SESSION['success_message'] = "Availability set successfully!";
            } else {
                throw new Exception("Failed to set availability.");
            }

            // Redirect to the dashboard with a success message
            header("Location: /TechnicianDashBoardPage");
            exit();
        } catch (Exception $e) {
            // Store the error message and reload the form
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: /TechnicianDashBoardPage");
            exit();
        }
    }

    // Load the form
    require_once(__DIR__ . "/../views/pages/TechnicianDashBoardPage.php");
}, ["get", "post"]);



