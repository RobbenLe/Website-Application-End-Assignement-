<?php

require_once(__DIR__ . "/../models/ServiceModel.php");

class ServiceController 
{
    private $serviceModel;

    public function __construct()
    {
        $this->serviceModel = new ServiceModel();
    }

    /**
     * Get all services grouped by category
     */
    public function getAllServices()
    {
        return $this->serviceModel->getAllServicesGroupedByCategory();
    }

    /**
     * Get services by a specific category
     */
    public function getServicesByCategory($category)
    {
        return $this->serviceModel->getServicesByCategory($category);
    }

    /**
     * Get service details by ID
     */
    public function getServiceById($service_id)
    {
        return $this->serviceModel->getServiceById($service_id);
    }

    /**
     * Update an existing service
     */
    public function updateService($id, $name, $category, $price, $duration)
    {
        return $this->serviceModel->updateService($id, $name, $category, $price, $duration);
    }


    public function getAllCategories() {
        try {
            return $this->serviceModel->getAllCategories();
        } catch (Exception $e) {
            throw new Exception("Failed to retrieve categories: " . $e->getMessage());
        }
    }
        
    /**
     * Add a service
     */
    public function addService($name, $category, $price, $duration) {
        if (empty($name) || empty($category) || empty($price) || empty($duration)) {
            throw new Exception("All fields are required.");
        }
    
        if (!is_numeric($price) || $price <= 0) {
            throw new Exception("Invalid price format. Must be a positive number.");
        }
    
        if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $duration)) {
            throw new Exception("Invalid duration format. Must be HH:MM:SS.");
        }
    
        return $this->serviceModel->addService($name, $category, $price, $duration);
    }

    /**
     * Delete a service
     */
    public function deleteService($service_id) 
{
    if (empty($service_id)) {
        throw new Exception("Service ID is required to delete a service.");
    }
 
    try {
        return $this->serviceModel->deleteService($service_id);
    } catch (Exception $e) {
        throw new Exception("Failed to delete service: " . $e->getMessage());
    }
}


    
    
}
