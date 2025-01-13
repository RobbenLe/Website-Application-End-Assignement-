<?php
require_once(__DIR__ . "/../controllers/ServiceController.php");
require_once(__DIR__ . "/../controllers/UserController.php");
require_once(__DIR__ . "/../controllers/AppointmentController.php");

/**
 * ======================================
 * Route: Choose Time Page
 * ======================================
 */
Route::add('/ChooseTimePage', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Validate session and ensure the user is logged in
    if (!isset($_SESSION['is_logged_in']) || $_SESSION['is_logged_in'] !== true) {
        header("Location: /LoginPage");
        exit();
    }

    $userId = $_SESSION['user_id'];
    $technicians = (new UserController())->getAllTechnicians();
    require_once(__DIR__ . "/../views/pages/ChooseTimePage.php");
});

Route::add('/getTechnicianAvailabilityByDate', function () {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_GET['technician_id']) || !isset($_GET['selected_date']) || !isset($_GET['duration'])) {
        echo json_encode(["error" => "Technician ID, date, and duration are required."]);
        http_response_code(400);
        exit();
    }

    $technicianId = intval($_GET['technician_id']);
    $selectedDate = $_GET['selected_date'];
    $duration = intval($_GET['duration']);

    try {
        $appointmentController = new AppointmentController();
        $availability = $appointmentController->getAvailableTimeSlotsByDuration($technicianId, $selectedDate, $duration);

        echo json_encode($availability);
    } catch (Exception $e) {
        error_log("❌ Error fetching time slots: " . $e->getMessage());
        echo json_encode(["error" => $e->getMessage()]);
        http_response_code(500);
    }

    exit();
});


Route::add('/getSuggestedTimeSlots', function () {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        exit();
    }

    try {
        $technicianId = $_GET['technician_id'] ?? null;
        $date = $_GET['selected_date'] ?? null;
        $duration = $_GET['duration'] ?? null;

        if (!$technicianId || !$date || !$duration) {
            throw new Exception("Technician ID, date, and duration are required.");
        }

        $appointmentController = new AppointmentController();
        $slots = $appointmentController->getSuggestedTimeSlots($technicianId, $date, $duration);

        echo json_encode($slots);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
});




/**
 * ======================================
 * Route: Get Technician Availability (API Endpoint)
 * ======================================
 */
Route::add('/getTechnicianAvailabilityByDate', function () {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_GET['technician_id']) || !isset($_GET['selected_date'])) {
        echo json_encode(["error" => "Technician ID and date are required."]);
        http_response_code(400);
        exit();
    }

    $technicianId = intval($_GET['technician_id']);
    $selectedDate = $_GET['selected_date'];

    try {
        $userController = new UserController();
        $availability = $userController->getTechnicianAvailabilityByDate($technicianId, $selectedDate);

        echo json_encode($availability);
    } catch (Exception $e) {
        echo json_encode(["error" => $e->getMessage()]);
        http_response_code(500);
    }

    exit();
});

/**
 * ======================================
 * Route: Create Appointment
 * ======================================
 */
/**
 * Route: Create Appointment
 */
Route::add('/createAppointment', function () {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $input = json_decode(file_get_contents("php://input"), true);
            $customerId = $input['customerId'] ?? null;
            $technicianId = $input['technicianId'] ?? null;
            $selectedDate = $input['selectedDate'] ?? null;
            $startTime = $input['startTime'] ?? null;
            $endTime = $input['endTime'] ?? null;
            $serviceIds = $input['serviceIds'] ?? [];

            if (empty($customerId) || empty($technicianId) || empty($selectedDate) || empty($startTime) || empty($endTime) || empty($serviceIds)) {
                throw new Exception("All fields are required.");
            }

            $appointmentController = new AppointmentController();
            $appointmentId = $appointmentController->processAppointment(
                $customerId,
                $technicianId,
                $selectedDate,
                $startTime,
                $endTime,
                $serviceIds
            );

            echo json_encode(["success" => true, "appointmentId" => $appointmentId]);
        } catch (Exception $e) {
            error_log("Error in /createAppointment: " . $e->getMessage());
            echo json_encode(["success" => false, "error" => $e->getMessage()]);
        }
        exit();
    }
},['POST']);












