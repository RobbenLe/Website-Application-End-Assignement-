<?php

enum AppointmentStatus: string {
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Canceled = 'canceled';
}

class AppointmentDTO {
    public readonly int $id;
    public readonly ?int $customer_id;
    public readonly string $name;
    public readonly ?string $email;
    public readonly ?string $phone;
    public readonly int $service_id;
    public readonly int $technician_id;
    public readonly DateTime $appointment_date;
    public readonly AppointmentStatus $status;
    public readonly DateTime $created_at;

    public function __construct(
        int $id,
        ?int $customer_id=null,
        string $name,
        ?string $email=null,
        ?string $phone=null,
        int $service_id,
        int $technician_id,
        DateTime $appointment_date,
        AppointmentStatus $status,
        DateTime $created_at
    ) {
        $this->id = $id;
        $this->customer_id = $customer_id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->service_id = $service_id;
        $this->technician_id = $technician_id;
        $this->appointment_date = $appointment_date;
        $this->status = $status;
        $this->created_at = $created_at;
    }
}