<?php

require_once(__DIR__ . "/BaseModel.php");

class TechnicianModel extends BaseModel 
{
    public function __construct()
    {
        parent::__construct();
    } 

    // Fetch appointments assigned to a technician for a specific date
    public function getAppointmentsByDate($technicianId, $date)
    {
        $query = "
            SELECT a.id AS appointment_id, c.name AS customer_name, 
                   a.start_time, a.end_time, 
                   GROUP_CONCAT(s.name) AS services
            FROM appointments a
            JOIN customers c ON a.customer_id = c.id
            JOIN appointment_services as ON a.id = as.appointment_id
            JOIN services s ON as.service_id = s.id
            WHERE a.technician_id = :technicianId AND DATE(a.appointment_date) = :date
            GROUP BY a.id
        ";

        $stmt = self::$pdo->prepare($query);
        $stmt->execute(['technicianId' => $technicianId, 'date' => $date]);

        return $stmt->fetchAll();
    }

    // Add or update a technician's availability
    public function setAvailability($technicianId, $date, $startTime, $endTime)
{
    try {
        $query = "
            INSERT INTO technician_availability (technician_id, available_date, available_start_time, available_end_time)
            VALUES (:technicianId, :date, :startTime, :endTime)
            ON DUPLICATE KEY UPDATE available_start_time = :startTime, available_end_time = :endTime
        ";
        $stmt = self::$pdo->prepare($query);
        $stmt->execute([
            'technicianId' => $technicianId,
            'date'         => $date,
            'startTime'    => $startTime,
            'endTime'      => $endTime,
        ]);

        return $stmt->rowCount(); // Returns the number of affected rows
    } catch (Exception $e) {
        error_log("Model Error: " . $e->getMessage());
        throw $e; // Re-throw to propagate error
    }
}

}
