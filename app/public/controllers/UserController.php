<?php

require_once(__DIR__ . "/../models/UserModel.php");

class UserController{
  private $userModel;

  public function __construct()
  {
      $this->userModel = new UserModel();
  }

  public function getUserByUsername($username) 
  {
    return $this->userModel->getUserByUsername($username);
  }

  public function register($email, $username, $password, $role = 'customer') 
  {
    try {
        // Call the createTechnician method if role is technician
        if ($role === 'technician') {
            return $this->userModel->createTechnician($username, $email, $password);
        }

        // Handle other roles if necessary
        return $this->userModel->register($email, $username, $password, $role);
    } catch (Exception $e) {
        error_log("Error in register: " . $e->getMessage());
        throw new Exception("Failed to register user.");
    }
  }

  public function login($username, $password) 
  {
    $user = $this->getUserByUsername($username);

    if ($user && password_verify($password, $user["password"])) {
        return [
            'id' => $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
    } else {
        throw new Exception("Invalid username or password.");
    }
  }

  public function getUserById($id) 
  {
    try {
        // Fetch the user by ID from the UserModel
        $user = $this->userModel->getUserById($id);

        // Check if user was found
        if ($user) {
            return $user; // Return the user details as an associative array
        } else {
            throw new Exception("User not found with ID: $id");
        }
    } catch (Exception $e) {
        // Handle errors gracefully
        return "Error: " . $e->getMessage();
    }
  }

  public function processLogin($username, $password)
  {
    try {
        // Validate inputs
        if (empty($username) || empty($password)) {
            throw new Exception("Username and password are required.");
        }

        // Authenticate the user using the UserModel directly
        $user = $this->userModel->login($username, $password);

        // Start a session if login is successful
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Store user details in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['is_logged_in'] = true;

          // Role-based redirection
          switch ($user['role']) {
            case 'customer':
                header("Location: /homePage");
                break;
            case 'technician':
                header("Location: /TechnicianDashboard");
                break;
            case 'admin':
                header("Location: /AdminDashboard");
                break;
            default:
                header("Location: /LoginPage");
                break;
            }
            exit();
    } catch (Exception $e) {
        // Handle errors and redirect back with an error message
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['login_error'] = $e->getMessage();
        header("Location: /LoginPage");
        exit();
    }
  }

  public function getAllTechnicians() {
    try {
        return $this->userModel->getAllTechnicians();
    } catch (Exception $e) {
        throw new Exception("Failed to fetch technicians: " . $e->getMessage());
    }
}

  public function getTechnicianAvailabilityByDate($technicianId, $selectedDate) {
    try {
        // Fetch availability slots broken down into hourly intervals
        $timeSlots = $this->userModel->getTechnicianAvailabilityByDate($technicianId, $selectedDate);

        return $timeSlots;
    } catch (Exception $e) {
        throw new Exception("Failed to fetch technician availability: " . $e->getMessage());
    }
}

//CURD of user 

public function getAllUsers() {
    return $this->userModel->getAllUsers();
}


/**
 * Create New Technician
 */
public function createTechnician($username, $email, $password) {
    try {
        if (empty($username) || empty($email) || empty($password)) {
            throw new Exception("All fields are required to create a technician.");
        }

        $technicianId = $this->userModel->createTechnician($username, $email, $password);
        return ["success" => true, "message" => "Technician created successfully.", "id" => $technicianId];
    } catch (Exception $e) {
        return ["success" => false, "message" => $e->getMessage()];
    }
}

/**
 * Update Technician
 */
public function updateTechnician($technicianId, $username = null, $email = null, $password = null) {
    try {
        return $this->userModel->updateTechnician($technicianId, $username, $email, $password);
    } catch (Exception $e) {
        throw new Exception("Failed to update technician: " . $e->getMessage());
    }
}

/**
 * Delete Technician
 */
public function deleteTechnician($userId) {
    try {
        $result = $this->userModel->deleteUser($userId); // Ensure `deleteUser` is implemented to include the `role` condition
        if ($result === 0) {
            throw new Exception("Failed to delete technician. Technician may not exist or is not a technician.");
        }
        return ["success" => true, "message" => "Technician deleted successfully."];
    } catch (Exception $e) {
        throw new Exception("Error deleting technician: " . $e->getMessage());
    }
}

// Fetch All Appointments for Logged-in Customer
// Fetch All Appointments for Logged-in Customer
public function getAppointments()
{
    // Check if the session is already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: /LoginPage");
        exit();
    }

    $userId = $_SESSION['user_id'];
    try {
        // Call the new method in UserModel to fetch appointments
        $appointments = $this->userModel->getAppointmentsByCustomerId($userId);

        if (empty($appointments)) {
            // Return a message if no appointments are found
            return ["success" => true, "message" => "No appointments found.", "appointments" => []];
        }

        // Return the appointments with a success status
        return ["success" => true, "appointments" => $appointments];

    } catch (Exception $e) {
        error_log("❌ Error fetching appointments: " . $e->getMessage());
        // Return an error message instead of an empty array
        return ["success" => false, "message" => "Error fetching appointments. Please try again later."];
    }
}

public function cancelAppointment($appointmentId) {
    // Check if the session is started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Ensure the user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: /LoginPage");
        exit();
    }

    try {
        // Call the model's cancelAppointment method
        $cancelResult = $this->userModel->cancelAppointment($appointmentId);

        if ($cancelResult) {
            // Success message
            $_SESSION['cancel_message'] = "Appointment canceled successfully.";
        } else {
            // Failure message
            $_SESSION['cancel_message'] = "Failed to cancel the appointment.";
        }
        
        // Fetch updated appointments
        $appointments = $this->getAppointments();
        
        // Pass the appointments data to the UserAppointment view
        require_once(__DIR__ . "/../views/pages/UserAppointment.php");
    } catch (Exception $e) {
        error_log("❌ Error canceling appointment: " . $e->getMessage());
        $_SESSION['cancel_message'] = "Error canceling appointment. Please try again later.";
        // Fetch updated appointments
        $appointments = $this->getAppointments();
        require_once(__DIR__ . "/../views/pages/UserAppointment.php");
    }
}


}