<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderLigneController extends AbstractController
{
    #[Route('/profil/orderLigne', name: 'orderLigne_index')]
    public function index(): Response
    {
        return $this->render('order_ligne/index.html.twig', [
            'controller_name' => 'OrderLigneController',
        ]);
    }
}
