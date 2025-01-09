<?php 

require_once(__DIR__ . "/../models/AppointmentModel.php");

class AppointmentController 
{
    private $appointmentModel;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
    }

    /**
     * Create an appointment
     */
 /**
     * Process Appointment Creation
     */
    public function processAppointment($customerId, $technicianId, $selectedDate, $startTime, $endTime, $serviceIds) {
        try {
            // Validate inputs
            if (!$customerId || !$technicianId || !$selectedDate || !$startTime || !$endTime || empty($serviceIds)) {
                throw new Exception("Missing required appointment data fields.");
            }
            
            // Validate time slot availability
            $this->validateTimeSlot($technicianId, $selectedDate, $startTime, $endTime);
    
            // Create Appointment
            $appointmentId = $this->appointmentModel->createAppointment(
                $customerId,
                $technicianId,
                $selectedDate,
                $startTime,
                $endTime
            );
    
            // Link Services to Appointment
            foreach ($serviceIds as $serviceId) {
                $this->appointmentModel->linkServiceToAppointment($appointmentId, $serviceId);
            }
    
            // Update Technician Availability
            $this->appointmentModel->blockTechnicianTimeSlot(
                $technicianId,
                $selectedDate,
                $startTime,
                $endTime
            );
    
            return ["success" => true, "message" => "Appointment created successfully"];
        } catch (Exception $e) {
            return ["success" => false, "error" => $e->getMessage()];
        }
    }
    
    
    
    private function validateTimeSlot($technicianId, $selectedDate, $startTime, $endTime) {
        $isAvailable = $this->appointmentModel->isTimeSlotAvailable(
            $technicianId,
            $selectedDate,
            $startTime,
            $endTime
        );
    
        if (!$isAvailable) {
            throw new Exception("The selected time slot is no longer available.");
        }
    }

    /**
 * Get Available Time Slots by Duration
 * 
 * @param int $technicianId
 * @param string $date
 * @param int $duration (in minutes)
 * @return array
 */
public function getAvailableTimeSlotsByDuration($technicianId, $date, $duration)
{
    try {
        if (!$technicianId || !$date || !$duration) {
            throw new Exception("Technician ID, date, and duration are required.");
        }

        // Call the correct model method
        $availableSlots = $this->appointmentModel->getAvailableTimeSlotsByDuration(
            $technicianId,
            $date,
            $duration
        );

        if (empty($availableSlots)) {
            return ["message" => "No available slots found for the specified duration."];
        }

        return $availableSlots;
    } catch (Exception $e) {
        error_log("❌ Error in getAvailableTimeSlotsByDuration: " . $e->getMessage());
        throw new Exception("Failed to fetch available time slots: " . $e->getMessage());
    }
}

public function getSuggestedTimeSlots($technicianId, $date, $duration) {
    try {
        $slots = $this->appointmentModel->getSuggestedTimeSlots($technicianId, $date, $duration);
        if (empty($slots)) {
            throw new Exception("No available time slots found. Please select another day.");
        }
        return $slots;
    } catch (Exception $e) {
        throw new Exception("Failed to fetch suggested slots: " . $e->getMessage());
    }
}


    

    private function calculateTotalDuration($service_ids) {
        $total_duration = 0;
        $serviceController = new ServiceController();
        
        foreach ($service_ids as $service_id) {
            $service = $serviceController->getServiceById($service_id);
            $total_duration += $service['duration'];
        }
        
        return $total_duration;
    }
    /**
     * Cancel an appointment
     */
    public function cancelAppointment($appointment_id)
    {
        return $this->appointmentModel->cancelAppointment($appointment_id);
    }

    /**
     * Get appointments for a customer
     */
    /**
     * Get All Appointments
     */
    public function getAllAppointments()
    {
        try {
            return $this->appointmentModel->getAllAppointments();
        } catch (Exception $e) {
            throw new Exception("Failed to fetch appointments: " . $e->getMessage());
        }
    }

    /**
     * Get available slots for a technician
     */
    public function getTechnicianAvailableSlots($technician_id, $date)
    {
        return $this->appointmentModel->getAvailableTimeSlotsByTechnician($technician_id, $date);
    }


}
