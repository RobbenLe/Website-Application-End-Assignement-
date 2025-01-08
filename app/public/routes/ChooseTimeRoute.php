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
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $userController = new UserController();
    $technicians = $userController->getAllTechnicians();
    $totalDuration = $_SESSION['total_duration'] ?? 0;

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
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["error" => "Method not allowed"]);
        exit();
    }

    try {
        $data = json_decode(file_get_contents("php://input"), true);

        if (
            empty($data['customer_id']) ||
            empty($data['technician_id']) ||
            empty($data['selected_date']) ||
            empty($data['start_time']) ||
            empty($data['end_time']) ||
            empty($data['service_ids'])
        ) {
            throw new Exception("Missing required fields in the request.");
        }

        error_log("✅ Data received: " . print_r($data, true));

        $appointmentController = new AppointmentController();
        $appointmentId = $appointmentController->processAppointment(
            $data['customer_id'],
            $data['technician_id'],
            $data['selected_date'],
            $data['start_time'],
            $data['end_time'],
            $data['service_ids']
        );

        error_log("✅ Appointment successfully created with ID: " . $appointmentId);

        echo json_encode([
            "success" => true,
            "appointmentId" => $appointmentId
        ]);
    } catch (Exception $e) {
        error_log("❌ Appointment Creation Failed: " . $e->getMessage());
        http_response_code(400); // Bad Request
        echo json_encode([
            "success" => false,
            "error" => "Failed to create appointment: " . $e->getMessage()
        ]);
    }
});
    




