<?php
require_once 'Customer';
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
