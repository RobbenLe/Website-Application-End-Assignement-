<?php

require_once(__DIR__ . "/BaseModel.php");

 class Customer extends BaseModel 
 {
    public function __construct() {
        parent::__construct();
    }
    
    public function getCustomersByTechnician(int $id) 
    {

    }

 }