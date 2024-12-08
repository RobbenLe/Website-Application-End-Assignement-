<?php

require_once(__DIR__ . "/BaseModel.php");

class ServiceModel extends BaseModel 
{
   public function __construct()
   {
      parent::__construct();
   }

   public function GetServiceByCategory($category) 
   {
      $statement = self::$pdo->prepare("SELECT 
      service_id, 
      service_name, 
      category, 
      price, 
      estimated_duration 
      FROM 
      services 
      WHERE 
      category = :category;");
      $statement->execute(["categoty" => $category]);
      return $statement->fetch(PDO::FETCH_ASSOC);
   }

   public function getServicesOrderedByCategory() 
   {
    $query =("SELECT category, service_name, price, duration_minute
        FROM services
        ORDER BY category ASC, service_name ASC;
    ");
    $statement = self::$pdo->prepare($query);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}