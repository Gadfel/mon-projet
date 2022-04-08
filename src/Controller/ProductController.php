<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/products/{id}', name: 'product_index')]
    public function index(ProductRepository $productRepository, int $id): Response
    {
        $products = $productRepository->findAll();
        $categoryId = $id ;
        return $this->render('product/index.html.twig', [
            'id' => $categoryId,
            'products' => $products,
        ]);
    }

    #[Route('/admin/products', name: 'admin_product_index')]
    public function adminIndex(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('admin/products.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/admin/product/create', name: 'product_create')]
    public function create(Request $request, ManagerRegistry $managerRegistry)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $infoImg = $form['img']->getData(); //récuperer les info de l'image
            $extentionImg = $infoImg->guessExtension(); //récuperer l'extention e l'image
            $nomImg = time() . '-1.' . $extentionImg; //créer un nom unique pour l'image 
            $infoImg->move($this->getParameter('dossier_photos_produits'), $nomImg); //télécharger l'image dans le dossier adéquat
            $product->setImg($nomImg); //définit le nom de l'image à mettre en bdd

            $manager = $managerRegistry->getManager();
            $manager->persist($product);
            $manager->flush();

            $this->addFlash('success', 'le produit a bien été ajouter'); // message de succes
            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render(
            'admin/productForm.html.twig',
            [
                'productForm' => $form->createView()
            ]
        );
    }

    #[Route('/admin/product/update/{id}', name: 'product_update')]
    public function update(ProductRepository $productRepository, int $id, Request $request, ManagerRegistry $managerRegistry)
    {
        $product = $productRepository->find($id); //récuperer l'id et du coup la product 
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $infoImg = $form['img']->getData();
            $nomOldImg = $product->getImg();
            if ($infoImg !== null) {
                $cheminOldImg = $this->getParameter('dossier_photos_produits') . '/' . $nomOldImg;
                if (file_exists($cheminOldImg)) {
                    unlink($cheminOldImg);
                }
                $extentionImg = $infoImg->guessExtension();
                $nomImg = time() . '-1.' . $extentionImg;
                $infoImg->move($this->getParameter('dossier_photos_produits'), $nomImg);
                $product->setImg($nomImg);
            } else {
                $product->setImg($nomOldImg);
            }
            $manager = $managerRegistry->getManager();
            $manager->persist($product);
            $manager->flush();
            $this->addFlash('success', 'Le product a bien été modifiée');
            return $this->redirectToRoute('admin_product_index');
        }

        return $this->render('admin/productForm.html.twig', [
            'productForm' => $form->createView()
            /*'product' => $product*/
        ]);
    }

    #[Route('/admin/product/delete/{id}', name: 'product_delete')]
    public function delete(
        ProductRepository $productRepository,
        int $id,
        ManagerRegistry $managerRegistry
    ) {
        $product = $productRepository->find($id); // récuperer le product à suprimer en bdd
        $nomImg = $product->getImg();
        if ($nomImg !== null) {
            $chemainImg = $this->getParameter('dossier_photos_produits') . '/' . $nomImg; //reconstituer  le chemain de l'image
            if (file_exists($chemainImg)) { //verifier si le fichier existe
                unlink($chemainImg); // suprimer les images 
            }
        }
        $manager = $managerRegistry->getManager();
        $manager->remove($product);
        $manager->flush();
        $this->addFlash('success', 'le product à été bien suprimée'); // message de succés 
        return $this->redirectToRoute('admin_product_index');
    }
}
