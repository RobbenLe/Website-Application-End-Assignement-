<?php

require_once(__DIR__ . "/../models/TechnicianModel.php");

class TechnicianController 
{
    private $technicianModel;

    public function __construct()
    {
        $this->technicianModel = new TechnicianModel();
    }

    /**
     * Get appointments for a technician by date
     *
     * @param int $technicianId
     * @param string $date
     * @return array
     */
    public function getAppointmentsByDate($technicianId, $date)
    {
        try {
            return $this->technicianModel->getAppointmentsByDate($technicianId, $date);
        } catch (Exception $e) {
            error_log("Error fetching appointments: " . $e->getMessage());
            throw new Exception("Unable to fetch appointments. Please try again later.");
        }
    }

    /**
     * Set a technician's availability
     *
     * @param int $technicianId
     * @param string $date
     * @param string $startTime
     * @param string $endTime
     * @return bool
     */
    public function setAvailability($technicianId, $date, $startTime, $endTime)
{
    try {
        $technicianModel = new TechnicianModel();
        return $technicianModel->setAvailability($technicianId, $date, $startTime, $endTime);
    } catch (Exception $e) {
        error_log("Controller Error: " . $e->getMessage());
        throw $e; // Re-throw to propagate error
    }
}

}
