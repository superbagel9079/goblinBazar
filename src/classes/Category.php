<?php

class Category {

    private string $id;
    private string $name;
    private string $description;

    public function __construct(string $name, string $description) {
        $this->id = uniqid();
        $this->name = $name;
        $this->description = $description;
    }

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }

    public function __toString(): string {
        return "Category: {$this->name} (ID: {$this->id})";
    }
}
?>
