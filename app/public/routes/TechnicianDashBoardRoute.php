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
        header('Content-Type: application/json');

        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Get input from JSON
            $data = json_decode(file_get_contents("php://input"), true);
            $availableDates = $data['available_dates'] ?? [];
            $startTime = $data['start_time'] ?? null;
            $endTime = $data['end_time'] ?? null;
            $technicianId = $_SESSION['user_id'] ?? null;

            // Validate input
            if (!$technicianId) {
                throw new Exception("Technician is not logged in.");
            }
            if (empty($availableDates) || empty($startTime) || empty($endTime)) {
                throw new Exception("All fields are required.");
            }

            // Save availability for multiple dates
            $technicianController = new TechnicianController();
            $failedDates = [];

            foreach ($availableDates as $date) {
                $success = $technicianController->setAvailability($technicianId, $date, $startTime, $endTime);
                if (!$success) {
                    $failedDates[] = $date;
                }
            }

            // Prepare response message
            if (count($failedDates) > 0) {
                echo json_encode(["success" => false, "error" => "Failed to set availability for: " . implode(", ", $failedDates)]);
                http_response_code(400);
            } else {
                echo json_encode(["success" => true, "message" => "Availability set successfully!"]);
            }
            exit();
        } catch (Exception $e) {
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
            http_response_code(400);
            exit();
        }
    }

    // Load the Technician Dashboard page
    require_once(__DIR__ . "/../views/pages/TechnicianDashBoardPage.php");
}, ["get", "post"]);



