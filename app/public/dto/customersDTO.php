<?php

class CustomerDTO {
    public readonly int $id;
    public readonly string $name;
    public readonly ?string $email;
    public readonly ?string $phone;
    public readonly ?string $password;
    public readonly DateTime $created_at;
     
    //for customer with account and for customer without log in
    public function __construct(int $id, string $name, ?string $email=null, ?string $phone=null, ?string $password=null, DateTime $created_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->created_at = $created_at;
    }
    
}

// $customerWithoutLogin = new CustomerDTO(193, "John",null,null,null, new DateTime());