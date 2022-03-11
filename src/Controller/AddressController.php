<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\ProfilType;
use App\Repository\AddressRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    #[Route('/profil', name: 'address_index')]
    public function index(AddressRepository $addressRepository, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $address = $addressRepository->find($this->getUser());
        $form = $this->createForm(ProfilType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();
            $manager->persist($address);
            $manager->flush();
        }

        return $this->render('address/index.html.twig', [
            'profilForm' => $form->createView()
        ]);
    }

    #[Route('/admin/address', name: 'admin_address_index')]
    public function adminIndex(AddressRepository $addressRepository): Response
    {
        $addresse = $addressRepository->findAll();
        return $this->render('admin/addresse.html.twig', [
            'addresse' => $addresse,
        ]);
    }

    #[Route('/admin/addresse/create', name: 'address_create')]
    public function create(Request $request, ManagerRegistry $managerRegistry)
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);  
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {


            $manager = $managerRegistry->getManager();
            $manager->persist($address);
            $manager->flush();

            $this->addFlash('success', 'L\'addresse a bien été ajoutée');
            return $this->redirectToRoute('admin_address_index');

        }
        return $this->render('admin/addressForm.html.twig', [
            'addressForm' => $form->createView()
        ]);
    }
    #[Route('/admin/address/delete/{id}', name: 'address_delete')]
    public function delete(
        AddressRepository $addressRepository,
        int $id,
        ManagerRegistry $managerRegistry
    ) {
        $address = $addressRepository->find($id); // récuperer l'address à suprimer en bdd

        $manager = $managerRegistry->getManager();
        $manager->remove($address);
        $manager->flush();
        $this->addFlash('success', 'l\'addresse à été bien suprimée'); 
        return $this->redirectToRoute('admin_address_index');
    }     
}
