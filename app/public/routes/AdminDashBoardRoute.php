<?php
require_once(__DIR__ . "/../controllers/ServiceController.php");
require_once(__DIR__ . "/../controllers/UserController.php");
require_once(__DIR__ . "/../controllers/AppointmentController.php");
require_once(__DIR__ . "/../lib/SessionHelper.php");

Route::add('/AdminDashBoardPage', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['is_logged_in']) || $_SESSION['role'] !== 'technician') {
        header("Location: /LoginPage");
        exit();
    }

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

//get categories from the service
Route::add('/api/getCategories', function () {
    header('Content-Type: application/json');
    try {
        $serviceController = new ServiceController();
        $categories = $serviceController->getAllCategories();
        echo json_encode(["success" => true, "categories" => $categories]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
});




// Route to Add a New Service
Route::add('/api/addService', function () {
    header('Content-Type: application/json');

    $data = json_decode(file_get_contents('php://input'), true);

    $name = $data['name'] ?? '';
    $category = $data['category'] ?? '';
    $price = $data['price'] ?? '';
    $duration = $data['duration'] ?? '';

    if (empty($name) || empty($category) || empty($price) || empty($duration)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit();
    }

    if (!is_numeric($price) || $price <= 0) {
        echo json_encode(["success" => false, "message" => "Invalid price format."]);
        exit();
    }

    if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $duration)) {
        echo json_encode(["success" => false, "message" => "Invalid duration format."]);
        exit();
    }

    try {
        $serviceController = new ServiceController();
        $serviceController->addService($name, $category, $price, $duration);
        echo json_encode(["success" => true, "message" => "Service added successfully."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}, ["post"]);

//Route to delete dervice by id 
Route::add('/api/deleteService', function () {
    header('Content-Type: application/json');

    // Decode the JSON input
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if service_id is present
    $service_id = $data['service_id'] ?? null;

    if (empty($service_id)) {
        echo json_encode(["success" => false, "message" => "Service ID is required."]);
        exit();
    }

    try {
        $serviceController = new ServiceController();
        $rowsAffected = $serviceController->deleteService($service_id);

        if ($rowsAffected > 0) {
            echo json_encode(["success" => true, "message" => "Service deleted successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to delete service. Service may not exist."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}, ["post"]);


// Update Service
Route::add('/api/updateService', function () {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);

    $id = $data['id'];
    $name = $data['name'];
    $category = $data['category'];
    $price = $data['price'];
    $duration = $data['duration'];

    try {
        $serviceController = new ServiceController();
        $result = $serviceController->updateService($id, $name, $category, $price, $duration);

        if ($result > 0) {
            echo json_encode(['success' => true, 'message' => 'Service updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No changes made or service not found.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}, ['post']);






?>
