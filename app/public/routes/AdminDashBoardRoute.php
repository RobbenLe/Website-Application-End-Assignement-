<?php
require_once(__DIR__ . "/../controllers/ServiceController.php");
require_once(__DIR__ . "/../controllers/UserController.php");

// ✅ Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Route to Display the Admin Dashboard Page
Route::add('/AdminDashboard', function () {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        require_once(__DIR__ . "/../views/pages/AdminDashboardPage.php");
    } else {
        header('Location: /LoginPage');
        exit();
    }
});

// -------------------- USER ROUTES --------------------

// ✅ Route to Fetch All Users (For Admin)
Route::add('/api/getUsers', function () {
    header('Content-Type: application/json');
    $userController = new UserController();
    try {
        echo json_encode($userController->getAllUsers());
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
});

// ✅ Route to Update User Role
Route::add('/api/updateUserRole', function () {
    header('Content-Type: application/json');
    $userController = new UserController();
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'], $data['role'])) {
        echo json_encode(["success" => false, "message" => "Missing required parameters"]);
        exit();
    }

    try {
        $result = $userController->updateUserRole($data['id'], $data['role']);
        echo json_encode(["success" => true, "message" => "User role updated"]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}, ["post"]);

// ✅ Route to Delete a User
Route::add('/api/deleteUser', function () {
    header('Content-Type: application/json');
    $userController = new UserController();
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        echo json_encode(["success" => false, "message" => "Missing required parameters"]);
        exit();
    }

    try {
        $userController->deleteUser($data['id']);
        echo json_encode(["success" => true, "message" => "User deleted"]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}, ["post"]);

// -------------------- SERVICE ROUTES --------------------

// ✅ Route to Fetch All Services
Route::add('/api/getServices', function () {
    header('Content-Type: application/json');
    $serviceController = new ServiceController();
    try {
        echo json_encode($serviceController->getAllServices());
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
});

// ✅ Route to Add a New Service
Route::add('/api/addService', function () {
    header('Content-Type: application/json');
    $serviceController = new ServiceController();
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['name'], $data['category'], $data['price'], $data['duration'])) {
        echo json_encode(["success" => false, "message" => "Missing required parameters"]);
        exit();
    }

    try {
        $serviceController->addService($data['name'], $data['category'], $data['price'], $data['duration']);
        echo json_encode(["success" => true, "message" => "Service added successfully"]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}, ["post"]);

// ✅ Route to Update a Service
Route::add('/api/updateService', function () {
    header('Content-Type: application/json');
    $serviceController = new ServiceController();
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'], $data['name'], $data['category'], $data['price'], $data['duration'])) {
        echo json_encode(["success" => false, "message" => "Missing required parameters"]);
        exit();
    }

    try {
        $serviceController->updateService($data['id'], $data['name'], $data['category'], $data['price'], $data['duration']);
        echo json_encode(["success" => true, "message" => "Service updated successfully"]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}, ["post"]);

// ✅ Route to Delete a Service
Route::add('/api/deleteService', function () {
    header('Content-Type: application/json');
    $serviceController = new ServiceController();
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        echo json_encode(["success" => false, "message" => "Missing required parameters"]);
        exit();
    }

    try {
        $serviceController->deleteService($data['id']);
        echo json_encode(["success" => true, "message" => "Service deleted successfully"]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}, ["post"]);
