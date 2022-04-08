<?php

namespace App\Controller;

use App\Service\CartService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_index')]
    public function index(CartService $cartService, ProductRepository $productRepository): Response
    {
        
        $cart = $cartService->getCart();
        $total = $cartService->getTotal();
        $latestProducts = $productRepository->findBy([], ['id' => 'DESC'], 3);
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total,
            'latestProducts' => $latestProducts
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(CartService $cartService, int $id): Response
    {
      
    
        $cartService->add($id);
        return $this->redirectToRoute('cart_index'); // redirection
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(CartService $cartService, int $id): Response
    {
        $cartService->remove($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/delete/{id}', name: 'cart_delete')]
    public function delete(CartService $cartService, int $id): Response
    {
        $cartService->delete($id);
        return $this->redirectToRoute('cart_index');
    }

    #[Route('/cart/clear', name: 'cart_clear')]
    public function clear(CartService $cartService): Response
    {
        $cartService->clear();
        return $this->redirectToRoute('cart_index');
    }
}

