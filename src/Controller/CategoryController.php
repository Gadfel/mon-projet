<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/categories', name: 'category_index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[Route('/admin/category', name: 'admin_category_index')]
    public function adminIndex(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('admin/categories.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route('/admin/categories/create', name: 'category_create')]
    public function create(Request $request, ManagerRegistry $managerRegistry)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);  
        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){
            $infoImg = $form['img']->getData(); //récuperer les info de l'image
            $extentionImg = $infoImg->guessExtension(); //récuperer l'extention de l'image
            $nomImg = time() . '-1.' . $extentionImg; //créer un nom unique pour l'image 
            $infoImg->move($this->getParameter('dossier_photos_produits'), $nomImg); //télécharger l'image dans le dossier adéquat
            $category->setImg($nomImg); //définit le nom de l'image à mettre en bdd
            
            $manager = $managerRegistry->getManager();
            $manager->persist($category);
            $manager->flush();

            $this->addFlash('success', 'la categorie  a bien été ajouter'); // message de succes
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/categoryForm.html.twig', [
                'categoryForm' => $form->createView()
           
         ]);
    
    }

    #[Route('/admin/category/update/{id}', name: 'category_update')]
    public function update(CategoryRepository $categoryRepository, int $id, Request $request, ManagerRegistry $managerRegistry)
    {
        $category = $categoryRepository->find($id); //récuperer l'id et du coup la category 
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $infoImg = $form['img']->getData();
            $nomOldImg = $category->getImg();
            if ($infoImg !== null) {
                $cheminOldImg = $this->getParameter('dossier_photos_produits') . '/' . $nomOldImg;
                if (file_exists($cheminOldImg)) {
                    unlink($cheminOldImg);
                }
                $extentionImg = $infoImg->guessExtension();
                $nomImg = time() . '-1.' . $extentionImg;
                $infoImg->move($this->getParameter('dossier_photos_produits'), $nomImg);
                $category->setImg($nomImg);
            } else {
                $category->setImg($nomOldImg);
            }
            $manager = $managerRegistry->getManager();
            $manager->persist($category);
            $manager->flush();
            $this->addFlash('success', 'La categorie a bien été modifiée');
            return $this->redirectToRoute('admin_category_index');
        }
        return $this->render('admin/categoryForm.html.twig', [
            'categoryForm' => $form->createView(),
            /*'category' => $category*/
        ]);


    }  
    #[Route('/admin/category/delete/{id}', name: 'category_delete')]
    public function delete(
        CategoryRepository $categoryRepository,
        int $id,
        ManagerRegistry $managerRegistry
    ) {
        $category = $categoryRepository->find($id); // récuperer la categorie à suprimer en bdd
        $nomImg = $category->getImg();
        if ($nomImg !== null) {
            $chemainImg = $this->getParameter('dossier_photos_produits') . '/' . $nomImg; //reconstituer  le chemain de l'image
            if (file_exists($chemainImg)) { //verifier si le fichier existe
                unlink($chemainImg); // suprimer les images 
            }
        } 

        $manager = $managerRegistry->getManager();
        $manager->remove($category);
        $manager->flush();
        $this->addFlash('success', 'la categorie à été bien suprimée'); // message de succés 
        return $this->redirectToRoute('admin_category_index');
    }     

}