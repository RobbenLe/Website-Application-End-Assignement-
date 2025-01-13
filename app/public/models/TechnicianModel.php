<?php

require_once(__DIR__ . "/BaseModel.php");

class TechnicianModel extends BaseModel 
{
    public function __construct()
    {
        parent::__construct();
    } 

    public function getAppointmentsByDate($technicianId, $date)
{
    $query = "
        SELECT 
            a.id AS appointment_id, 
            u.username AS customer_username, 
            a.appointment_start_time AS start_time, 
            a.appointment_end_time AS end_time, 
            GROUP_CONCAT(s.name) AS services
        FROM 
            appointments a
        JOIN 
            users u ON a.customer_id = u.id
        JOIN 
            appointment_services aps ON a.id = aps.appointment_id
        JOIN 
            services s ON aps.service_id = s.id
        WHERE 
            a.technician_id = :technicianId 
            AND DATE(a.appointment_date) = :date
        GROUP BY 
            a.id
    ";

    try {
        $stmt = self::$pdo->prepare($query);
        $stmt->execute(['technicianId' => $technicianId, 'date' => $date]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($result)) {
            error_log("Model: No appointments found for Technician ID: $technicianId on Date: $date");
        } else {
            error_log("Model: Appointments fetched: " . json_encode($result));
        }

        return $result;
    } catch (Exception $e) {
        error_log("Model error in getAppointmentsByDate: " . $e->getMessage());
        throw new Exception("Database query failed: " . $e->getMessage());
    }
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
