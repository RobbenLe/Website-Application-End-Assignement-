<?php
enum Role : string {
    case Admin = 'admin';
    case Technician = 'technician';
}
 class NailTechnicianDTO {
    public readonly int $id;
    public readonly string $name;
    public readonly string $email;
    public readonly ?string $phone;
    public readonly string $password;
    public readonly bool $is_available;
    public readonly Role $role;
    public readonly DateTime $created_at;

    public function __construct(int $id, string $name, string $email, ?string $phone=null, string $password, bool $is_available, Role $role, DateTime $created_at)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->password = $password;
        $this->is_available = $is_available;
        $this->role = $role;
        $this->created_at = $created_at;
    }
 }