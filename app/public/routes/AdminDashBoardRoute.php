<?php
require_once(__DIR__ . "/../controllers/ServiceController.php");
require_once(__DIR__ . "/../controllers/UserController.php");
require_once(__DIR__ . "/../controllers/AppointmentController.php");

// ✅ Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

Route::add('/AdminDashBoardPage', function () {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userController = new UserController(); // Ensure this is instantiated correctly

        $formType = $_POST['form-type'] ?? ''; // Identify the form type

        try {
            switch ($formType) {
                case 'create-technician':
                    // Validate input
                    if (empty($_POST['tech-username']) || empty($_POST['tech-email']) || empty($_POST['tech-password'])) {
                        throw new Exception("All fields are required to create a technician.");
                    }

                    // Call the UserController to create the technician
                    $userController->register(
                        $_POST['tech-email'],
                        $_POST['tech-username'],
                        $_POST['tech-password'],
                        'technician'
                    );

                    $_SESSION['success_message'] = "Technician created successfully!";
                    break;

                // Handle other form types
                default:
                    throw new Exception("Invalid form type.");
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            error_log("Error in Admin Dashboard Page: " . $e->getMessage());
        }

        // Redirect to the admin dashboard
        header('Location: /AdminDashBoardPage');
        exit();
    }

    require_once(__DIR__ . "/../views/pages/AdminDashboardPage.php");
});

//create Technician
Route::add('/api/createTechnician', function () {
    header('Content-Type: application/json');

    // Instantiate UserController
    $userController = new UserController();

    // Parse JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    try {
        // Validate input
        if (!isset($data['username'], $data['email'], $data['password'])) {
            throw new Exception("All fields (username, email, password) are required.");
        }

        // Call UserController to create the technician
        $result = $userController->createTechnician(
            $data['username'],
            $data['email'],
            $data['password']
        );

        // Return success response
        echo json_encode(["success" => true, "message" => $result['message'], "technician_id" => $result['id']]);
    } catch (Exception $e) {
        // Return error response
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}, ["post"]);


//Update Technician
Route::add('/api/updateTechnician', function () {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);

    if (empty($data['id'])) {
        echo json_encode(["success" => false, "message" => "Technician ID is required"]);
        exit();
    }

    try {
        $userController = new UserController();
        $result = $userController->updateTechnician(
            $data['id'],
            $data['username'] ?? null, // Optional
            $data['email'] ?? null,    // Optional
            $data['password'] ?? null  // Optional
        );
        echo json_encode(["success" => true, "message" => "Technician updated successfully"]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}, ["post"]);




// ✅ Route to Delete a Technician
Route::add('/api/deleteTechnician', function () {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        echo json_encode(["success" => false, "message" => "Technician ID is required."]);
        exit();
    }

    try {
        $userController = new UserController();
        $response = $userController->deleteTechnician($data['id']);
        echo json_encode(["success" => true, "message" => $response['message']]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}, ["post"]);




// ✅ Consolidated Route to Fetch All Admin Dashboard Data
Route::add('/api/getAdminDashboardData', function () {
    header('Content-Type: application/json');

    $userController = new UserController();
    $serviceController = new ServiceController();
    $appointmentController = new AppointmentController();

    try {
        echo json_encode([
            "success" => true,
            "technicians" => $userController->getAllTechnicians(),
            "services" => $serviceController->getAllServices(),
            "appointments" => $appointmentController->getAllAppointments(),
        ]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
});


// ✅ Route to Fetch All Technicians
Route::add('/api/getTechnicians', function () {
    header('Content-Type: application/json');
    $userController = new UserController();

    try {
        $technicians = $userController->getAllTechnicians();
        echo json_encode(["success" => true, "technicians" => $technicians]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
});

// ✅ Service Management Routes
Route::add('/api/addService', function () {
    header('Content-Type: application/json');
    $serviceController = new ServiceController();
    $data = json_decode(file_get_contents('php://input'), true);

    try {
        if (isset($data['name'], $data['category'], $data['price'], $data['duration'])) {
            $serviceController->addService($data['name'], $data['category'], $data['price'], $data['duration']);
            echo json_encode(["success" => true, "message" => "Service added successfully"]);
        } else {
            throw new Exception("Missing required parameters");
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}, ["post"]);

Route::add('/api/updateService', function () {
    header('Content-Type: application/json');
    $serviceController = new ServiceController();
    $data = json_decode(file_get_contents('php://input'), true);

    try {
        if (isset($data['id'], $data['name'], $data['category'], $data['price'], $data['duration'])) {
            $serviceController->updateService(
                $data['id'],
                $data['name'],
                $data['category'],
                $data['price'],
                $data['duration']
            );
            echo json_encode(["success" => true, "message" => "Service updated successfully"]);
        } else {
            throw new Exception("Missing required parameters");
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}, ["post"]);

Route::add('/api/deleteService', function () {
    header('Content-Type: application/json');
    $serviceController = new ServiceController();
    $data = json_decode(file_get_contents('php://input'), true);

    try {
        if (isset($data['id'])) {
            $serviceController->deleteService($data['id']);
            echo json_encode(["success" => true, "message" => "Service deleted successfully"]);
        } else {
            throw new Exception("Missing required parameters");
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}, ["post"]);

?>
