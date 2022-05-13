<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentionsLigalesController extends AbstractController
{
    #[Route('/mentions/ligales', name: 'mentions_ligales')]
    public function index(): Response
    {
        return $this->render('mentions_ligales/index.html.twig', [
            'controller_name' => 'MentionsLigalesController',
        ]);
    }
}
