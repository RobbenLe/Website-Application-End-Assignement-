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
    $stmt = self::$pdo->prepare("SELECT username, password, role FROM users WHERE username = :username");
    $stmt->execute(["username" => $username]);       // Pass the value in an array to bind securely
    return $stmt->fetch(PDO::FETCH_ASSOC);          // Fetch the user data
  }
 }