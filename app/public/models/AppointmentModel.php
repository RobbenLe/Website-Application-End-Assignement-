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
    try {
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

        // Return the ID of the newly created appointment
        return self::$pdo->lastInsertId();
    } catch (PDOException $e) {
        throw new Exception("Failed to create appointment: " . $e->getMessage());
    }
}



// Link Services to Appointment
public function linkServiceToAppointment($appointmentId, $serviceId) {
    try {
        $query = "INSERT INTO appointment_services (appointment_id, service_id) 
                  VALUES (:appointment_id, :service_id)";
        
        $stmt = self::$pdo->prepare($query);
        $stmt->execute([
            'appointment_id' => $appointmentId,
            'service_id' => $serviceId
        ]);
    } catch (PDOException $e) {
        throw new Exception("Failed to link service to appointment: " . $e->getMessage());
    }
}


/**
 * Block Technician Time Slot
 */
public function blockTechnicianTimeSlot($technicianId, $selectedDate, $startTime, $endTime) {
    try {
        $query = "INSERT INTO technician_unavailability (technician_id, unavailable_date, start_time, end_time) 
                  VALUES (:technician_id, :unavailable_date, :start_time, :end_time)";
        
        $stmt = self::$pdo->prepare($query);
        $stmt->execute([
            'technician_id' => $technicianId,
            'unavailable_date' => $selectedDate,
            'start_time' => $startTime,
            'end_time' => $endTime
        ]);
    } catch (PDOException $e) {
        throw new Exception("Failed to block technician time slot: " . $e->getMessage());
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
public function getAvailableTimeSlotsByDuration($technicianId, $date, $duration) {
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

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    error_log("Available slots: " . json_encode($results));
    return $results;
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
    while ($startTime < $endTime) {
        $slotEndTime = clone $startTime;
        $slotEndTime->add($slotDuration);

        if ($slotEndTime > $endTime) {
            break;
        }

        $suggestedSlots[] = [
            'start' => $startTime->format('H:i'),
            'end' => $slotEndTime->format('H:i')
        ];

        // Increment start time for the next slot
        $startTime->add($slotDuration);
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
     * Get All Appointments
     * Fetches all appointments with customer and technician names.
     */
    public function getAllAppointments()
    {
        $query = "
            SELECT 
                a.id AS appointment_id,
                c.username AS customer_name,
                t.username AS technician_name,
                a.appointment_date,
                a.appointment_start_time,
                a.appointment_end_time,
                a.appointment_status,
                GROUP_CONCAT(s.name SEPARATOR ', ') AS services
            FROM 
                appointments a
            LEFT JOIN 
                users c ON a.customer_id = c.id AND c.role = 'customer'
            LEFT JOIN 
                users t ON a.technician_id = t.id AND t.role = 'technician'
            LEFT JOIN 
                appointment_services asg ON a.id = asg.appointment_id
            LEFT JOIN 
                services s ON asg.service_id = s.id
            GROUP BY 
                a.id
            ORDER BY 
                a.appointment_date ASC, a.appointment_start_time ASC
        ";

        $stmt = self::$pdo->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update appointment status
     */
        public function updateAppointmentStatus($appointmentId, $newStatus)
    {
        $query = "
            UPDATE appointments 
            SET appointment_status = :newStatus 
            WHERE id = :appointmentId
        ";

        $stmt = self::$pdo->prepare($query);
        $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_STR);
        $stmt->bindParam(':appointmentId', $appointmentId, PDO::PARAM_INT);

        return $stmt->execute();
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

