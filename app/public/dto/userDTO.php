<?php
class UserDTO {
    private int $id;
    private string $username;
    private string $password;
    private string $email;
    private string $role;  // 'customer', 'technician', or 'admin'

    // Constructor
    public function __construct(int $id, string $username, string $password, string $email, string $role) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->role = $role;
    }

    // Getters and Setters
    public function getId(): int { return $this->id; }
    public function getUsername(): string { return $this->username; }
    public function getPassword(): string { return $this->password; }
    public function getEmail(): string { return $this->email; }
    public function getRole(): string { return $this->role; }

    public function setId(int $id): void { $this->id = $id; }
    public function setUsername(string $username): void { $this->username = $username; }
    public function setPassword(string $password): void { $this->password = $password; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setRole(string $role): void { $this->role = $role; }
}