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
     * Add a new service
     */
    public function addService($service_name, $category, $price, $duration)
    {
        try {
            return $this->serviceModel->addService($service_name, $category, $price, $duration);
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * Update an existing service
     */
    public function updateService($service_id, $service_name, $category, $price, $duration)
    {
        try {
            return $this->serviceModel->updateService($service_id, $service_name, $category, $price, $duration);
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * Delete a service by ID
     */
    public function deleteService($service_id)
    {
        try {
            return $this->serviceModel->deleteService($service_id);
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
