<?php

require_once(__DIR__ . "/../models/TechnicianModel.php");

class TechnicianController 
{
    private $technicianModel;

    public function __construct()
    {
        $this->technicianModel = new TechnicianModel();
    }

    public function getAppointmentsByDate($technicianId, $date)
{
    try {
        if (empty($technicianId) || empty($date)) {
            throw new InvalidArgumentException("Technician ID and date are required.");
        }

        $appointments = $this->technicianModel->getAppointmentsByDate($technicianId, $date);

        if (empty($appointments)) {
            error_log("Controller: No appointments found for Technician ID: $technicianId on Date: $date");
        } else {
            error_log("Controller: Appointments fetched: " . json_encode($appointments));
        }

        return $appointments;
    } catch (InvalidArgumentException $e) {
        error_log("Validation error in getAppointmentsByDate: " . $e->getMessage());
        throw $e;
    } catch (Exception $e) {
        error_log("Controller error in getAppointmentsByDate: " . $e->getMessage());
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
