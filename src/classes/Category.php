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

<?php
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

<?php
class Cart {

    private array $items = [];
    private Customer $customer;

    public function __construct(Customer $customer) {
        $this->customer = $customer;
    }

    public function addProduct(Product $product, int $quantity = 1): void {
        
        if ($quantity <= 0) {
            throw new InvalidArgumentException("Quantity must be at least 1.");
        }

        $id = $product->getId();

        $currentQty = isset($this->items[$id]) ? $this->items[$id]['quantity'] : 0;
        $totalRequested = $currentQty + $quantity;

        if ($totalRequested > $product->getStock()) {
            throw new Exception(
                "Insufficient stock for '{$product->getName()}'. " .
                "Available: {$product->getStock()}, Requested total: {$totalRequested}."
            );
        }

        $this->items[$id] = [
            'product' => $product,
            'quantity' => $totalRequested
        ];
    }

    public function updateQuantity(Product $product, int $quantity): void {
        
        $id = $product->getId();

        if (!isset($this->items[$id])) {
            throw new Exception("Product '{$product->getName()}' is not in the cart.");
        }

        if ($quantity <= 0) {
            $this->removeProduct($product);
            return;
        }

        if ($quantity > $product->getStock()) {
            throw new Exception("Insufficient stock to set quantity to {$quantity}.");
        }

        $this->items[$id]['quantity'] = $quantity;
    }

    public function removeProduct(Product $product): void {
        $id = $product->getId();
        
        if (isset($this->items[$id])) {
            unset($this->items[$id]);
        }
    }

    public function getTotal(): float {
        $total = 0.0;

        foreach ($this->items as $item) {
            $product = $item['product'];
            $qty = $item['quantity'];

            $total += $product->getPrice() * $qty;
        }

        return $total;
    }

    public function clear(): void {
        $this->items = [];
    }

    public function getItems(): array {
        return $this->items;
    }

    public function getCustomer(): Customer {
        return $this->customer;
    }
}
?>
