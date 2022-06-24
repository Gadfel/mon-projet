<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\MachineRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(MachineRepository $machineRepository, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $machines = $machineRepository->findBy([], ['id' => 'DESC'], 2);

        $products = $productRepository->findAll();

        $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'machines' => $machines,
            'product' => $products,
            'category' => $categories
        ]);
    }

    #[Route('politique_confidentialité',name:'politique')]
    public function pc():Response 
    {
        return  $this->render('politiconfid.html.twig');
    }
}