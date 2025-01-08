<?php

require_once(__DIR__ . "/BaseModel.php");

class UserModel extends BaseModel 
{
    public function __construct() {
        parent::__construct();
    }
    
    // Fetch user by username
    public function getUserByUsername($username)
    {
        $stmt = self::$pdo->prepare("SELECT id, username, email, password, role FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
     
    // Register a new user
    public function register($email, $username, $password, $role = 'customer') {
        $sql = "INSERT INTO users (email, username, password, role)
        VALUES (:email, :username, :password, :role)";
    
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
        $stmt = self::$pdo->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->bindParam(":role", $role);
    
        if ($stmt->execute()) {
            return self::$pdo->lastInsertId();
        } else {
            throw new Exception("Failed to register user.");
        }
    }

    // Login a user
    public function login($username, $password) {
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

    // Fetch user by ID
    public function getUserById($id) {
        $stmt = self::$pdo->prepare("SELECT id, username, email, role FROM users WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllTechnicians() {
      $query = "SELECT id AS technician_id, username AS technician_name 
                FROM users 
                WHERE role = 'technician'";
  
      $stmt = self::$pdo->prepare($query);
      $stmt->execute();
  
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

    // Get Technician Availability by Date
    public function getTechnicianAvailabilityByDate($technicianId, $selectedDate) {
        $query = "SELECT available_start_time, available_end_time 
                  FROM technician_availability 
                  WHERE technician_id = :technician_id 
                  AND available_date = :selected_date
                  ORDER BY available_start_time ASC";

        $stmt = self::$pdo->prepare($query);
        $stmt->bindParam(':technician_id', $technicianId, PDO::PARAM_INT);
        $stmt->bindParam(':selected_date', $selectedDate, PDO::PARAM_STR);
        $stmt->execute();

        $availability = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Break down time slots into hourly intervals
        $timeSlots = [];
        foreach ($availability as $slot) {
            $startTime = strtotime($slot['available_start_time']);
            $endTime = strtotime($slot['available_end_time']);

            while ($startTime < $endTime) {
                $nextHour = strtotime('+1 hour', $startTime);
                if ($nextHour > $endTime) {
                    break;
                }
                $timeSlots[] = [
                    'available_start_time' => date('H:i', $startTime),
                    'available_end_time' => date('H:i', $nextHour)
                ];
                $startTime = $nextHour;
            }
        }

        return $timeSlots;
    }

  

    //CURD of User
    // UserModel.php

/**
 * Update User Role
 */
public function updateUserRole($userId, $newRole) {
  $query = "UPDATE users SET role = :role WHERE id = :id";
  $statement = self::$pdo->prepare($query);
  $statement->execute([
      "id" => $userId,
      "role" => $newRole
  ]);
  return $statement->rowCount();
}

/**
* Delete User
*/
public function deleteUser($userId) {
  $query = "DELETE FROM users WHERE id = :id";
  $statement = self::$pdo->prepare($query);
  $statement->execute([
      "id" => $userId
  ]);
  return $statement->rowCount();
}

/**
* Fetch All Users
*/
public function getAllUsers() {
  $query = "SELECT id, username, email, role FROM users";
  $statement = self::$pdo->prepare($query);
  $statement->execute();
  return $statement->fetchAll(PDO::FETCH_ASSOC);
}

}
