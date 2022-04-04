<?php

namespace App\Controller;

use Stripe\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;

class OrderController extends AbstractController
{
    #[Route('/profil/order', name: 'order_index')]
    public function index(OrderRepository $orderRepository, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $order = $orderRepository->find($this->getUser());
        $form = $this->createForm(OrderType::class,$order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $manager = $managerRegistry->getManager();
            $manager->persist($order);
            $manager->flush();
        }
        return $this->render('order/index.html.twig', [
            'orderForm' => $form->createView()
        ]);
    }

    // #[Route('/admin/order/create', name: 'order_create')]
    // public function create(Request $request, ManagerRegistry $managerRegistry){

    //     $order = new Order();
    //     $form = $this->createForm(OrderType::class, $order);
    //     $form ->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()){

    //         $manager = $managerRegistry->getManager();
    //         $manager->persist($order);
    //         $manager->flush();

    //         $this->addflash('success','la commande à été bien Ajouter');
    //         return $this->redirectToRoute('admin_order_index');
    //     }
    //     return $this->render('admin/orderForm.html.twig',[
    //         'orderForm' => $form->createView()
    //     ]);
    // }

    // #[Route('/admin/order/update/{id}', name:'order_update')]
    // public function update(OrderRepository $orderRepository, int $id, Request $request, ManagerRegistry $managerRegistry) 
    // {
    //     $order = $orderRepository->find($id);
    //     $form = $this->createForm(OrderType::class, $order);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()){

    //         $manager = $managerRegistry->getManager();
    //         $manager->persist($order);
    //         $manager->flush();

    //         $this->addflash('success','la commande à été bien Modifier');
    //         return $this->redirectToRoute('admin_order_index');
    //     }

    //     return $this->render('/admin/orderForm.html.twig',[
    //         'orderForm' =>$form->createView()

    //     ]);
    // }

    // #[Route('/admin/order/delete/{id}', name: 'order_delete')]
    // public function delete(OrderRepository $orderRepository, int $id, ManagerRegistry $managerRegistry)
    // {
    //     $order = $orderRepository->find($id);

    //     $manager = $managerRegistry->getManager();
    //     $manager->remove($order);
    //     $manager->flush();
    //     $this->addFlash('success','la commande à été bien suprimée');
    //     return $this->redirectToRoute('admin_order_index');
    // }
}
