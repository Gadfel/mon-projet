<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Address;
use App\Form\OrderType;
use App\Form\AddressType;
use App\Service\CartService;
use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\OrderLigne;

class CartController extends AbstractController

{
    #[Route('/cart', name: 'cart_index')]
    public function index(CartService $cartService, ProductRepository $productRepository,Request $request, ManagerRegistry $managerRegistry,SessionInterface $sessionInterface): Response
    {
        $order = new Order();
        $formAddress = $this->createForm(OrderType::class, $order);
        $formAddress-> handleRequest($request);
             

        if ($formAddress->isSubmitted() && $formAddress->isValid()) {

            $order->setDate(new \DateTime);
            $order->setUser($this->getUser());
            $order->setTotalAmount($cartService->getTotal());
            $manager = $managerRegistry->getManager();

             $cart = $cartService->getCart();

        
        foreach ($cart as $item) {
            $orderLigne = new OrderLigne;
            $orderLigne->setProduct($item['product']);
            $orderLigne->setOrders($order);
            $orderLigne->setQuantity($item['quantity']);
            
            $manager->persist($orderLigne);
        }
            $manager->persist($order);
            $sessionInterface->set('order', $order);
            $manager->flush();

            return $this->redirectToRoute('payment');  
        }

        $cart = $cartService->getCart();
        $total = $cartService->getTotal();
        $latestProducts = $productRepository->findBy([], ['id' => 'DESC'], 2);
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total,
            'latestProducts' => $latestProducts,
            'formAddress' => $formAddress->createView(),

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

    #[Route('/cart/address/create', name: 'cart_address_create')]
    public function createUserAddress(Request $request, ManagerRegistry $managerRegistry)
    {
        
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);  
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
           
            $address->setUser($this->getUser());
            $address->setFullAddress($address->getAddress(). ' ' .$address->getPostalCode(). ' ' .$address->getCity(). ' ' .$address->getCountry());
            $manager = $managerRegistry->getManager();
            $manager->persist($address);
            $manager->flush();

            $this->addFlash('success', 'L\'addresse a bien été ajoutée');
            return $this->redirectToRoute('cart_index');

        }
        return $this->render('cart/addressCart.html.twig', [
            'addressForm' => $form->createView()
        ]);
    }

   
}


