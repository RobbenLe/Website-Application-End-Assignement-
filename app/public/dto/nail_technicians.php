<?php
enum Role : string {
    case Admin = 'admin';
    case Technician = 'technician';
}
 class TechnicianDTO {
    private int $id;
    private UserDTO $user;
    private string $skills;  // e.g., "Gellak", "Biab", "Solar"
    private string $availability;  // Serialized availability data

    // Constructor
    public function __construct(int $id, UserDTO $user, string $skills, string $availability) {
        $this->id = $id;
        $this->user = $user;
        $this->skills = $skills;
        $this->availability = $availability;
    }

    // Getters and Setters
    public function getId(): int { return $this->id; }
    public function getUser(): UserDTO { return $this->user; }
    public function getSkills(): string { return $this->skills; }
    public function getAvailability(): string { return $this->availability; }

    public function setId(int $id): void { $this->id = $id; }
    public function setUser(UserDTO $user): void { $this->user = $user; }
    public function setSkills(string $skills): void { $this->skills = $skills; }
    public function setAvailability(string $availability): void { $this->availability = $availability; }
 }