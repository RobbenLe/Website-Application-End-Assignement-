<?php

require_once(__DIR__. "/BaseModel.php");
require_once(__DIR__ . "/../dto/appointmentDTO.php");

class AppointmentModel extends BaseModel 
{
  private static $pdo;

  public function __construct(PDO $pdo)
  {
      self::$pdo = $pdo;
  }

}
