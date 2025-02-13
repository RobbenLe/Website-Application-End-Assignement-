<?php

require_once(__DIR__ . "/BaseModel.php");

class ServiceModel extends BaseModel 
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Fetch all services grouped by category
     */
    public function getAllServicesGroupedByCategory() 
{
    $query = "SELECT 
                id, 
                name, 
                price, 
                duration, 
                category 
              FROM services 
              ORDER BY category ASC, name ASC";
    $statement = self::$pdo->prepare($query);
    $statement->execute();

    $services = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Group services by category
    $groupedServices = [];
    foreach ($services as $service) {
        $groupedServices[$service['category']][] = $service;
    }

    return $groupedServices;
}

    /**
     * Fetch services by category
     */
    public function getServicesByCategory($category) 
    {
        $query = "SELECT 
                    id,
                    name AS service_name, 
                    category, 
                    price, 
                    duration 
                  FROM services 
                  WHERE category = :category 
                  ORDER BY name ASC";
        $statement = self::$pdo->prepare($query);
        $statement->execute(["category" => $category]);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a single service by its ID
     */
    public function getServiceById($service_id) 
    {
        $query = "SELECT 
                    id, 
                    name AS service_name, 
                    category, 
                    price, 
                    duration 
                  FROM services 
                  WHERE id = :service_id";
        $statement = self::$pdo->prepare($query);
        $statement->execute(["service_id" => $service_id]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Add a new service
     */
    public function addService($service_name, $category, $price, $duration) 
    {
        $query = "INSERT INTO services 
                    (name, category, price, duration) 
                  VALUES 
                    (:service_name, :category, :price, :duration)";
        $statement = self::$pdo->prepare($query);
        $statement->execute([
            "service_name" => $service_name,
            "category" => $category,
            "price" => $price,
            "duration" => $duration
        ]);
        return self::$pdo->lastInsertId();
    }


    /**
     * Delete a service by ID
     */
    public function deleteService($service_id) 
    {
    $query = "DELETE FROM services WHERE id = :service_id";
    $statement = self::$pdo->prepare($query);
    $statement->execute(["service_id" => $service_id]);
    return $statement->rowCount();
    }

    
    /**
     * Create a service 
     */
    public function createService($name, $category, $price, $duration) {
        $query = "INSERT INTO services (name, category, price, duration) 
                  VALUES (:name, :category, :price, :duration)";
    
        $stmt = self::$pdo->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':duration', $duration);
    
        if (!$stmt->execute()) {
            throw new Exception("Failed to create service.");
        }
    }
    
    /**
     * Get service category
     */
    public function getAllCategories() {
        $query = "SELECT DISTINCT category FROM services";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Fetch as an indexed array
    }


       /**
     * Update an existing service
     */
    public function updateService($service_id, $service_name, $category, $price, $duration)
{
    $query = "UPDATE services 
              SET name = :service_name, 
                  category = :category, 
                  price = :price, 
                  duration = :duration 
              WHERE id = :service_id";
    $statement = self::$pdo->prepare($query);
    $statement->execute([
        "service_id" => $service_id,
        "service_name" => $service_name,
        "category" => $category,
        "price" => $price,
        "duration" => $duration
    ]);
    return $statement->rowCount();
}

    
    
}