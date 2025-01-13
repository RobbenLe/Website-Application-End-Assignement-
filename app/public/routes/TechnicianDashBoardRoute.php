<?php
require_once(__DIR__ . "/../controllers/TechnicianController.php"); 

Route::add('/TechnicianDashBoardPage', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'technician') {
        header("Location: /LoginPage");
        exit();
    }

    // Log the session details
    error_log("Technician Dashboard Session: " . json_encode($_SESSION));

    $technicianId = $_SESSION['user_id'];
    if (!$technicianId) {
        error_log("Technician Dashboard: User ID missing in session");
        header("Location: /LoginPage");
        exit();
    }

    require_once(__DIR__ . "/../views/pages/TechnicianDashBoardPage.php");
});



Route::add('/getAppointmentsForTechnician', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        error_log("Unauthorized access: No user ID in session.");
        http_response_code(401);
        echo json_encode(["error" => "Unauthorized access. Please log in."]);
        exit();
    }

    $technicianId = $_SESSION['user_id'];
    $date = $_GET['date'] ?? null;

    if (!$date) {
        error_log("Error: Missing date parameter.");
        http_response_code(400);
        echo json_encode(["error" => "Date is required."]);
        exit();
    }

    try {
        error_log("Fetching appointments for Technician ID: $technicianId, Date: $date");

        $technicianController = new TechnicianController();
        $appointments = $technicianController->getAppointmentsByDate($technicianId, $date);

        if (empty($appointments)) {
            error_log("No appointments found for Technician ID: $technicianId, Date: $date");
            http_response_code(200);
            echo json_encode(["message" => "No appointments found for the selected date."]);
        } else {
            error_log("Fetched Appointments: " . json_encode($appointments));
            echo json_encode($appointments);
        }
    } catch (Exception $e) {
        error_log("Error fetching appointments: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(["error" => "An error occurred while fetching appointments."]);
    }
    exit();
}, ["GET"]);








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



