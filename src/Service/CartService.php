<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $sessionInterface;
    protected $ProductRepository;

    public function __construct(SessionInterface $sessionInterface, ProductRepository $productRepository)
    {
        $this->sessionInterface = $sessionInterface;
        $this->productRepository = $productRepository;
    }
    public function empty() {}

    public function add(int $id): void
    {
        $cart = $this->sessionInterface->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->sessionInterface->set('cart', $cart);
    }

    public function remove(int $id): void
    {
        $cart = $this->sessionInterface->get('cart', []);
        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }
        $this->sessionInterface->set('cart', $cart);
    }

    public function delete(int $id): void
    {
        $cart = $this->sessionInterface->get('cart', []);
        if (!empty([$cart[$id]])) {
            unset($cart[$id]);
        }
        $this->sessionInterface->set('cart', $cart);
    }

    public function clear(): void
    {
        $this->sessionInterface->remove('cart');
    }

    public function getCart(): array
    {
        $sessionCart = $this->sessionInterface->get('cart', []);
        $cart = [];
        foreach ($sessionCart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            $element = [
                'product' => $product,
                'quantity' => $quantity,
                
            ];
            $cart[] = $element;
        }
        return $cart;
    }

    public function getTotal(): int
    {
        $sessionCart = $this->sessionInterface->get('cart', []);
        $total = 0;
        foreach ($sessionCart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            $total += $product->getPrice() * $quantity;
        }
        return $total;
    }

    public function getNbProducts(): int
    {
        $sessionCart = $this->sessionInterface->get('cart');
        $nb = 0;
        foreach ($sessionCart as $line) {
            $nb++;
        }
        return $nb;
    }
}
