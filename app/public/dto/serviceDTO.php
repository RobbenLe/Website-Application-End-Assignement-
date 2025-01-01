<?php
enum category : string {
    case Gellak = 'Gellak';
    case Biab = 'Biab';
    case Acrylic = 'Acrylic';
}

class ServiceDTO {
    private int $id;
    private string $name;
    private string $category;  // e.g., "Gellak", "Biab", "Solar"
    private float $price;
    private int $duration;  // Duration in minutes

    // Constructor
    public function __construct(int $id, string $name, string $category, float $price, int $duration) {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->price = $price;
        $this->duration = $duration;
    }

    // Getters and Setters
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getCategory(): string { return $this->category; }
    public function getPrice(): float { return $this->price; }
    public function getDuration(): int { return $this->duration; }

    public function setId(int $id): void { $this->id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    public function setCategory(string $category): void { $this->category = $category; }
    public function setPrice(float $price): void { $this->price = $price; }
    public function setDuration(int $duration): void { $this->duration = $duration; }
} 