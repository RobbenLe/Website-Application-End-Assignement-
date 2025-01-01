<?php

class CustomerDTO {
    private int $id;
    private UserDTO $user;
    private DateTime $createdAt;

    // Constructor
    public function __construct(int $id, UserDTO $user, DateTime $createdAt) {
        $this->id = $id;
        $this->user = $user;
        $this->createdAt = $createdAt;
    }

    // Getters and Setters
    public function getId(): int { return $this->id; }
    public function getUser(): UserDTO { return $this->user; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }

    public function setId(int $id): void { $this->id = $id; }
    public function setUser(UserDTO $user): void { $this->user = $user; }
    public function setCreatedAt(DateTime $createdAt): void { $this->createdAt = $createdAt; }
    
}

// $customerWithoutLogin = new CustomerDTO(193, "John",null,null,null, new DateTime());