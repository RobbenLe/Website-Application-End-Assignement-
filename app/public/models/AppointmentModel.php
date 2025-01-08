<?php

require_once(__DIR__. "/BaseModel.php");
require_once(__DIR__ . "/../dto/appointmentDTO.php");

class AppointmentModel extends BaseModel 
{
  public function __construct()
  {
      parent::__construct();
  }

  public function createAppointment($customerId, $technicianId, $appointmentDate, $startTime, $endTime) {
    $query = "INSERT INTO appointments (customer_id, technician_id, appointment_date, appointment_start_time, appointment_end_time, appointment_status) 
              VALUES (:customer_id, :technician_id, :appointment_date, :start_time, :end_time, 'pending')";
    
    $stmt = self::$pdo->prepare($query);
    $stmt->execute([
        'customer_id' => $customerId,
        'technician_id' => $technicianId,
        'appointment_date' => $appointmentDate,
        'start_time' => $startTime,
        'end_time' => $endTime
    ]);

    return self::$pdo->lastInsertId();
}

// Link Services to Appointment
public function linkServiceToAppointment($appointmentId, $serviceId) {
    $query = "INSERT INTO appointment_services (appointment_id, service_id) VALUES (:appointment_id, :service_id)";
    $stmt = self::$pdo->prepare($query);
    $stmt->execute([
        'appointment_id' => $appointmentId,
        'service_id' => $serviceId
    ]);
}

/**
 * Block Technician Time Slot
 */
public function blockTechnicianTimeSlot($technicianId, $selectedDate, $startTime, $endTime) {
    $query = "UPDATE technician_availability 
              SET available_start_time = :end_time 
              WHERE technician_id = :technician_id 
              AND available_date = :selected_date 
              AND available_start_time = :start_time";
    
    $stmt = self::$pdo->prepare($query);
    $stmt->execute([
        'technician_id' => $technicianId,
        'selected_date' => $selectedDate,
        'start_time' => $startTime,
        'end_time' => $endTime
    ]);
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
    $query = "SELECT available_start_time, available_end_time 
              FROM technician_availability 
              WHERE technician_id = :technician_id 
              AND available_date = :date 
              AND TIMEDIFF(available_end_time, available_start_time) >= SEC_TO_TIME(:duration * 60)";

    $stmt = self::$pdo->prepare($query);
    $stmt->execute([
        'technician_id' => $technicianId,
        'date' => $date,
        'duration' => $duration
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getSuggestedTimeSlots($technicianId, $date, $duration) {
    $query = "SELECT available_start_time, available_end_time 
              FROM technician_availability 
              WHERE technician_id = :technician_id 
              AND available_date = :date";

    $stmt = self::$pdo->prepare($query);
    $stmt->execute([
        'technician_id' => $technicianId,
        'date' => $date
    ]);

    $availability = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$availability) {
        throw new Exception("No available slots for this technician on the selected day.");
    }

    $startTime = new DateTime($availability['available_start_time']);
    $endTime = new DateTime($availability['available_end_time']);
    $slotDuration = new DateInterval('PT' . $duration . 'M'); // Total duration in minutes

    $suggestedSlots = [];
    while ($startTime->add($slotDuration) <= $endTime) {
        $suggestedSlots[] = [
            'start' => $startTime->format('H:i'),
            'end' => $startTime->add($slotDuration)->format('H:i')
        ];
    }

    return $suggestedSlots;
}



/**
 * Validate Time Slot Availability
 */
public function isTimeSlotAvailable($technicianId, $selectedDate, $startTime, $endTime) {
    $query = "SELECT COUNT(*) as count 
              FROM appointments 
              WHERE technician_id = :technician_id 
              AND appointment_date = :selected_date 
              AND ((appointment_start_time < :end_time AND appointment_end_time > :start_time))";

    $stmt = self::$pdo->prepare($query);
    $stmt->execute([
        'technician_id' => $technicianId,
        'selected_date' => $selectedDate,
        'start_time' => $startTime,
        'end_time' => $endTime
    ]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['count'] == 0;
}


     /**
     * Cancel an appointment
     */
    public function cancelAppointment($appointment_id) 
    {
        $query = "UPDATE appointments SET appointment_status = 'canceled' WHERE id = :appointment_id";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute(['appointment_id' => $appointment_id]);
        return $stmt->rowCount();
    }

    /**
     * Get all appointments for a specific customer
     */
    public function getAppointmentsByCustomerId($customer_id) 
    {
        $query = "SELECT * FROM appointments WHERE customer_id = :customer_id ORDER BY appointment_date DESC";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute(['customer_id' => $customer_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all appointments for a specific technician
     */
    public function getAppointmentsByTechnicianId($technician_id) 
    {
        $query = "SELECT * FROM appointments WHERE technician_id = :technician_id ORDER BY appointment_date ASC";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute(['technician_id' => $technician_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get details of a specific appointment by ID
     */
    public function getAppointmentById($appointment_id) 
    {
        $query = "SELECT * FROM appointments WHERE id = :appointment_id";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute(['appointment_id' => $appointment_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update appointment status
     */
    public function updateAppointmentStatus($appointment_id, $status) 
    {
        $query = "UPDATE appointments SET appointment_status = :status WHERE id = :appointment_id";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute([
            'status' => $status,
            'appointment_id' => $appointment_id
        ]);
        return $stmt->rowCount();
    }

    /**
     * Get available time slots for a technician on a specific date
     */
    public function getAvailableTimeSlotsByTechnician($technician_id, $date) 
    {
        $query = "SELECT available_start_time, available_end_time 
                  FROM technician_availability 
                  WHERE technician_id = :technician_id AND available_date = :date";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute([
            'technician_id' => $technician_id,
            'date' => $date
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

