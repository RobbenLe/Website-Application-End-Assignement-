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

    return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch user data
   }
     
  /**
     * Register a new user
     */
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
           // Return the newly created user ID
           return self::$pdo->lastInsertId();
      } else {
           throw new Exception("Failed to register user.");
      }
    }

     /**
     * Login a user
     */
    public function login($username, $password) {
      $user = $this->getUserByUsername($username);
      
      if ($user && password_verify($password, $user["password"])) {
        return [
          'id' => $user['id'],
          'username' => $user['username'],
          'email' => $user['email'],
          'role' => $user['role'],
        ];
      }else {
        throw new Exception("Invalid username or password.");
      }
    }

    public function getUserById($id) {
      $stmt = self::$pdo->prepare("SELECT id, username, email, role FROM users WHERE id = :id");
      $stmt->bindParam(":id", $id);
      $stmt->execute();

      return $stmt->fetch(PDO::FETCH_ASSOC);
    }
 }