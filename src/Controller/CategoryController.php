<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category_index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'category' => $categories,
        ]);
    }
    #[Route('/admin/category', name: 'admin_category_index')]
    public function adminIndex(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('admin/category.html.twig', [
            'category' => $categories
        ]);
    }

    #[Route('/admin/categories/create', name: 'category_create')]
    public function create(Request $request, ManagerRegistry $managerRegistry)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);  
        $form->handleRequest($request); 
    
}
}