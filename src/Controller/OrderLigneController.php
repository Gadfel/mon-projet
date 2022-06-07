<?php

namespace App\Controller;

use App\Entity\OrderLigne;
use App\Form\OrderLigneType;
use App\Repository\OrderRepository;
use App\Repository\OrderLigneRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderLigneController extends AbstractController
{
    #[Route('/profil/orderLigne', name: 'orderLigne_index')]
    public function index(): Response
    {
        return $this->render('order_ligne/index.html.twig', [
            'controller_name' => 'OrderLigneController',
        ]);
    }

    #[Route('/profil/order/{id}', name: 'order_detail')]
    public function show(OrderRepository $orderRepository,OrderLigneRepository $orderLigneRepository, int $id): Response
    {
        $orderLignes = $orderLigneRepository->findBy(['orders' => $id]);
        $order = $orderRepository->findOneBy(['id' => $id]);
        return $this->render('profil/order/detail.html.twig', [
            'orderLignes' => $orderLignes,
            'order' => $order
        ]);
    }

    #[Route('/profil/orderLigne/create', name: 'orderLigne_create')]
    public function create(Request $request, ManagerRegistry $managerRegistry){


        $order = new OrderLigne();
       
        $form = $this->createForm(OrderLigneType::class, $order);
        $form ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){


            $manager = $managerRegistry->getManager();
            $manager->persist($order);
            $manager->flush();

            $this->addflash('success','la  ligne de commande à été bien Ajouter');
            return $this->redirectToRoute('profil_order_detail_index');
        }
        return $this->render('profil/orderLigneForm.html.twig',[
            'orderLigneForm' => $form->createView()
        ]);
        
    }

    #[Route('/profil/orderLigne/liste', name: 'profil_orderLigne_liste')]
    public function userListeOrderLigne(OrderLigneRepository $orderLigneRepository)
    {
        $listeOrderLigne = $orderLigneRepository->findBy(['user'=>$this->getUser()]);

        return $this->render('profil/Order/detail.html.twig', [
            
            'orderLignes' => $listeOrderLigne
        ]);
    }
}
