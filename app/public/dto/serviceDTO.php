<?php
enum category : string {
    case Gellak = 'Gellak';
    case Biab = 'Biab';
    case Acrylic = 'Acrylic';
}

class ServiceDTO {
    public readonly int $id;
    public readonly string $name;
    public readonly category $category;
    public readonly float $price;
    public readonly int $duration_minute;
    
    public function __construct(
        int $id,
        string $name,
        Category $category,
        float $price,
        int $duration_minute
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->category = $category;
        $this->price = $price;
        $this->duration_minute = $duration_minute;
    }
} 