<?php

enum AppointmentStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Completed = 'completed';
    case Canceled = 'canceled';
}

class AppointmentDTO {
    private int $id;
    private CustomerDTO $customer;
    private TechnicianDTO $technician;
    private ServiceDTO $service;
    private DateTime $appointmentDate;
    private string $appointmentStatus;  // e.g., 'pending', 'confirmed', 'completed', 'canceled'
    private DateTime $createdAt;

    // Constructor
    public function __construct(int $id, CustomerDTO $customer, TechnicianDTO $technician, ServiceDTO $service, DateTime $appointmentDate, string $appointmentStatus, DateTime $createdAt) {
        $this->id = $id;
        $this->customer = $customer;
        $this->technician = $technician;
        $this->service = $service;
        $this->appointmentDate = $appointmentDate;
        $this->appointmentStatus = $appointmentStatus;
        $this->createdAt = $createdAt;
    }

    // Getters and Setters
    public function getId(): int { return $this->id; }
    public function getCustomer(): CustomerDTO { return $this->customer; }
    public function getTechnician(): TechnicianDTO { return $this->technician; }
    public function getService(): ServiceDTO { return $this->service; }
    public function getAppointmentDate(): DateTime { return $this->appointmentDate; }
    public function getAppointmentStatus(): string { return $this->appointmentStatus; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }

    public function setId(int $id): void { $this->id = $id; }
    public function setCustomer(CustomerDTO $customer): void { $this->customer = $customer; }
    public function setTechnician(TechnicianDTO $technician): void { $this->technician = $technician; }
    public function setService(ServiceDTO $service): void { $this->service = $service; }
    public function setAppointmentDate(DateTime $appointmentDate): void { $this->appointmentDate = $appointmentDate; }
    public function setAppointmentStatus(string $appointmentStatus): void { $this->appointmentStatus = $appointmentStatus; }
    public function setCreatedAt(DateTime $createdAt): void { $this->createdAt = $createdAt; }
}