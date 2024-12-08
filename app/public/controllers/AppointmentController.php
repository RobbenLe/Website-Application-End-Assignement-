<?php 

require_once (__DIR__ . "../models/AppointmentModel.php");

 class AppointmentController 
{
    private $appointmentModel;
    public function __construct(){
        $this->appointmentModel = new AppointmentModel();
    }  
}