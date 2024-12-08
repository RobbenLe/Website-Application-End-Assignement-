<?php

require_once(__DIR__. "/BaseModel.php");

class Appointment extends BaseModel 
{
    public function __construct() 
    {
      parent::__construct();  
    }

    public function getAppointmentByTechnicianId($id) //Get all of appointments reference Technician ID
    {
       $statement = self::$pdo->prepare("SELECT 
    a.appointment_id, 
    a.appointment_date, 
    s.price, 
    c.name AS customer_name, 
    c.email AS customer_email, 
    c.phone AS customer_phone, 
    s.name AS service_name
    FROM 
    appointments a
    JOIN 
    customers c ON a.customer_id = c.customer_id
    JOIN 
    services s ON a.service_id = s.service_id
    WHERE 
    a.nail_technician_id = :technician_id
    ORDER BY 
    a.appointment_date ASC;");
      $statement->execute(["technician_id" => $id]);
      returN $statement->fetch(PDO::FETCH_ASSOC);
    }


  
    //Create new appointments
    public function insertAppointment($customer_id, $customer_name, $email, $phone, $service_id, $technician_id, $appointment_date, $appointment_status, $created_at) 
    {
       if ($customer_id != null) { //if customer with account book appoinment.
        $query = "INSERT INTO appointments (customer_id, customer_name, email, phone, service_id, technician_id, appointment_date, appointment_status, created_at ) 
        VALUE (:customer_id, :customer_name, :email, :phone, :service_id, :technician_id, :appointment_date, :appointment_status, :created_at)";
        $statement = self::$pdo->prepare($query);
        $statement->execute([
          "customer_id" => $customer_id,
          "customer_name" => $customer_name,
          "email" => $email,
          "phone" => $phone,
          "service_id" =>$service_id,
          "technician_id" => $technician_id,
          "appointment_date" => $appointment_date,
          "appointment_status" => $appointment_status,
          "created_at" => $created_at
        ]);
       }
       else { //if customer book appoinment directly
        $query = "INSERT INTO appointments (customer_name, email, phone, service_id, technician_id, appointment_date, appointment_status, created_at )
        VALUE (:customer_name, :email, :phone, :service_id, :technician_id, :appointment_date, :appointment_status, :created_at)";
        $statement = self::$pdo -> prepare($query);
        $statement->execute([
          "customer_name" => $customer_name,
          "email" => $email,
          "phone" => $phone,
          "service_id" =>$service_id,
          "technician_id" => $technician_id,
          "appointment_date" => $appointment_date,
          "appointment_status" => $appointment_status,
          "created_at" => $created_at
        ]);
       }
    }
}