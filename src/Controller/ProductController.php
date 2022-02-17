<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'product_index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'product' => $products,
        ]);
    }
    #[Route('/admin/products', name: 'admin_product_index')]
    public function adminIndex(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('admin/product.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/admin/product/create', name: 'product_create')]
    public function create(Request $request, ManagerRegistry $managerRegistry)
    {
        $product = new Product(); 
        $form = $this->createForm(ProductType::class, $product); 
        $form->handleRequest($request); 

    }
    
}