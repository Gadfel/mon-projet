<?php

namespace App\Controller;

use DateTime;
use App\Entity\OrderLigne;
use App\Service\CartService;
use App\Form\CartConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




class OrderConfirmationController extends AbstractController
{
  protected $cartService;
  protected $em;

  public function __construct(CartService $cartService, EntityManagerInterface $em)
  {

    $this->cartService = $cartService;
    $this->em = $em;
  }

   
  #[Route('/confirm', name: 'confirm')]
  /**
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour confirmer une commandes ");
     */

  public function confirm(Request $request  )
  {
     $form = $this->createForm(CartConfirmationType::class);
     $form->handleRequest($request);

     if (!$form->isSubmitted()){
       $this->addFlash('Warning', 'Vous devez replir le formulaire de confirmation'); 
       return $this->redirectToRoute('cart_add');
     }

     $user = $this->getUser();

     $cart =$this->cartService->getCart();

     if (count($cart) === 0) {

        $this->addFlash('Warning', 'Vous ne pouver confirmer une commande avec un panier vide');

        return $this->redirectToRoute('cart_add');
     }

     /**
      * @var Order*/ //creer une commande 
      $order = $form->getData();
      
       
      $order->setUser($user)
        ->setOrderDate(new DateTime());
       

      $this->em->persist($order);

      foreach($this->cartServic as $order =>$cart){
        $orderLigne = new OrderLigne;
        $orderLigne->setOrders($order)
         ->setProduct($cart->Product())
         ->setProduct($cart->product->getPrice())
         ->setQuantity($cart->quantity);
        //  ->setTotalAmount($cartService->getTotal())
        //  ->setProductPrice($cart->product->getPrice())
      }
      

      $this->em->flush();
      $this->cartService->empty();

      $this->addFlash('success', 'La Commande a bien été enregistrée');
      return $this->redirectToRoute('');

  }  
}