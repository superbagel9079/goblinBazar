<?php
class Customer {

    private string $id;
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $address;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $address
    ) {
        $this->id = uniqid();
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->address = $address;
    }

    public function getFirstName(): string { return $this->firstName; }
    public function getEmail(): string { return $this->email; } 
    public function getAddress(): string { return $this->address; }

    public function __toString(): string {
        return "{$this->firstName} {$this->lastName} ({$this->email})";
    }
}
?>
