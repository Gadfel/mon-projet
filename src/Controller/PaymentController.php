<?php

namespace App\Controller;

use Stripe\StripeClient;
use App\Service\CartService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'payment')]
    public function index(Request $request, SessionInterface $sessionInterface, ProductRepository $productRepository): Response
    {

        if ($request->headers->get('referer') !== 'https://127.0.0.1:8000/cart') {
            return $this->redirectToRoute('cart_index');
        }

        $cart = $sessionInterface->get('cart'); // récupération du panier en session
        $stripeCart = [];

        foreach ($cart as $id => $quantity) { // transformation du panier session en panier Stripe
            $product = $productRepository->find($id);
            $stripeElement = [
                'amount' => $product->getPrice() * 100,
                'quantity' => $quantity,
                'currency' => 'EUR',
                'name' => $product->getName()
            ];
            $stripeCart[] = $stripeElement;
        }

        $stripe = new StripeClient('sk_test_51KbMkwDRnyIG5zQG5HNaZ19VL8BHubDQ5t9CWwRs5hI3Np689ycAnVZjWjptJRELqnEkNIox7CfZB4sgTmfgrIXW002K21VmkV');

        $stripeSession = $stripe->checkout->sessions->create([
            'line_items' => $stripeCart,
            'mode' => 'payment',
            'success_url' => 'https://127.0.0.1:8000/payment/success',
            'cancel_url' => 'https://127.0.0.1:8000/payment/cancel',
            'payment_method_types' => ['card']
        ]);
        return $this->render('payment/index.html.twig', [
            'sessionId' => $stripeSession->id,
        ]);
    }

    #[Route('/payment/success', name: 'payment_success')]
    public function success(Request $request, CartService $cartService): Response
    {
        if ($request->headers->get('referer') !== 'https://checkout.stripe.com/') {
            return $this->redirectToRoute('cart_index');
        }
        $cartService->clear();
        return $this->render('payment/success.html.twig');
    }


    #[Route('/payment/cancel', name: 'payment_cancel')]
    public function cancel(Request $request): Response
    {
        if ($request->headers->get('referer') !== 'https://checkout.stripe.com/') {
            return $this->redirectToRoute('cart_index');
        }
        return $this->render('payment/cancel.html.twig');
    }
}
