<?php

namespace App\Controller;

use Dompdf\Dompdf;
use App\Entity\Order;
use App\Entity\Invoice;
use Stripe\StripeClient;
use App\Entity\OrderLigne;
use App\Entity\InvoiceLine;
use App\Service\CartService;
use App\Repository\InvoiceRepository;
use App\Repository\ProductRepository;
use App\Repository\InvoiceLineRepository;
use Doctrine\Persistence\ManagerRegistry;
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

        if ($request->headers->get('referer') !== 'https://gadfeldistrib.fr/cart') {
            return $this->redirectToRoute('cart_index');
        }

        $cart = $sessionInterface->get('cart'); // récupération du panier en session
        
        $stripeCart = []; //intialiser le panier pour le stripe

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

        $stripe = new StripeClient($this->getParameter('app.stripe_sk'));

        $stripeSession = $stripe->checkout->sessions->create([
            'line_items' => $stripeCart,
            'mode' => 'payment',
            'success_url' => 'https://gadfeldistrib.fr/payment/success',
            'cancel_url' => 'https://gadfeldistrib.fr/payment/cancel',
            'payment_method_types' => ['card']
        ]);
        return $this->render('payment/index.html.twig', [
            'sessionId' => $stripeSession->id,
        ]);
    }


    #[Route('/payment/success', name: 'payment_success')]
    public function success(Request $request, InvoiceRepository $invoiceRepository, CartService $cartService, ManagerRegistry $managerRegistry, ProductRepository $productRepository): Response
    {
        if ($request->headers->get('referer') !== 'https://checkout.stripe.com/') {
            return $this->redirectToRoute('cart_index');
        }
        

        
        // génère un numéro de facture
        $invoices = $invoiceRepository->findAll();
        $invoiceNumbers = [];
        foreach ($invoices as $invoice) {
            array_push($invoiceNumbers, $invoice->getNumber());
        }
        $i = 1;
        $invoiceNumber = 'F' . date_format(new \DateTime(), 'Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);
        if (in_array($invoiceNumber, $invoiceNumbers)) {
            $i++;
            $invoiceNumber = 'F' . date_format(new \DateTime(), 'Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT);
        }

        // //crée une nouvelle facture en bdd
        // $invoice = new Invoice;
        // $invoice->setUser($this->getUser());
        // $invoice->setNumber($invoiceNumber);
        // $invoice->setPaymentDate(new \DateTime());
        // $invoice->setAmount($cartService->getTotal());
        // $manager = $managerRegistry->getManager();
        // $manager->persist($invoice);

        // // crée une ligne de facture pour chaque élément du panier
        // $cart = $cartService->getCart();
        // foreach ($cart as $item) {
        //     $invoiceLine = new InvoiceLine;
        //     $invoiceLine->setProduct($item['product']);
        //     $invoiceLine->setInvoice($invoice);
        //     $invoiceLine->setQuantity($item['quantity']);
        //     $manager->persist($invoiceLine);
        // }

        // $manager->flush();

        $cartService->clear();
        return $this->render('payment/success.html.twig', [
            'invoiceId' => $invoice->getId()
        ]);
    }


    #[Route('/payment/cancel', name: 'payment_cancel')]
    public function cancel(Request $request): Response
    {
        if ($request->headers->get('referer') !== 'https://checkout.stripe.com/') {
            return $this->redirectToRoute('cart_index');
        }
        return $this->render('payment/cancel.html.twig');
    }

    #[Route('invoice/download/{id}', name: 'invoice_download')]
    public function downloadInvoice(InvoiceRepository $invoiceRepository, int $id, InvoiceLineRepository $invoiceLineRepository): Response
    {
        $invoice = $invoiceRepository->find($id);

        // redirige si l'utilisateur connecté n'est pas le "propriétaire" de la facture
        if ($invoice->getUser() !== $this->getUser()) {
            return $this->render('bundles/TwigBundle/Exception/error403.html.twig');
        }

        // génère une facture au format pdf
        $dompdf = new Dompdf(); // instantier la classe
        $dompdf->loadHtml($this->renderView('payment/invoice.html.twig', [
            'invoice' => $invoice,
            'cart' => $invoiceLineRepository->findBy(['invoice' => $id]),
            'total' => $invoice->getAmount(),
            'user' => $invoice->getUser()
        ])); // donner le code HTML à Dompdf
        $dompdf->setPaper('A4', 'portrait'); // optionnel : donner la taille de papier et l'orientation
        $dompdf->render(); // rendre le HTML en tant que PDF
        $dompdf->stream($invoice->getNumber()); // affiche le PDF dans le navigateur
        return $this->render('payment/invoice.html.twig', [
            'invoice' => $invoice,
            'cart' => $invoiceLineRepository->findBy(['invoice' => $id]),
            'total' => $invoice->getAmount(),
            'user' => $invoice->getUser()
        ]);
    }
}
