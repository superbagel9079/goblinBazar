<?php
require_once 'Category.php';
class Product {

    private string $id;
    private string $name;
    private float $price;
    private Category $category;
    private int $stock;

    public function __construct(
        Category $category,
        string $name,
        float $price = 0.0,
        int $stock = 0
    ) {
        $this->category = $category;
        $this->id = uniqid();
        $this->name = $name;
        $this->price = $price;
        $this->stock = $stock;
    }

    public function getId(): string { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getStock(): int { return $this->stock; }
    public function getPrice(): float { return $this->price; }

    public function isAvailable(): bool {
        return $this->stock > 0;
    }

    public function reduceStock(int $quantity): int {
        if ($quantity < 0) {
            throw new InvalidArgumentException("Quantity cannot be negative.");
        }

        if ($quantity > $this->stock) {
            throw new RuntimeException("Insufficient stock for {$this->name}. Available: {$this->stock}");
        }

        $this->stock -= $quantity;
        return $this->stock;
    }

    public function __toString(): string {
        return "Product: {$this->name} | Price: {$this->price} $ | Stock: {$this->stock}";
    }
}
?>
