<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\ProfilType;
use App\Form\AddressType;
use App\Service\CartService;
use App\Repository\AddressRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AddressController extends AbstractController
{
    #[Route('/profil/address', name: 'profil_address_index')]
    public function index(AddressRepository $addressRepository, Request $request, ManagerRegistry $managerRegistry)
    {
        $address = $addressRepository->findBy(['user'=>$this->getUser()]);
        $form = $this->createForm(ProfilType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager = $managerRegistry->getManager();
            $manager->persist($address);
            $manager->flush();
        }

        return $this->render('profil/address/index.html.twig', [
            'addressForm' => $form->createView(),
        ]);
    }

    #[Route('/profil/address/liste', name: 'profil_address_liste')]
    public function userListeAddress(AddressRepository $addressRepository)
    {
        $listeAddress = $addressRepository->findBy(['user'=>$this->getUser()]);

        return $this->render('profil/address/index.html.twig', [
            
            'address' => $listeAddress
        ]);
    }

    #[Route('/profil/address/create', name: 'profil_address_create')]
    public function createUserAddress(Request $request, ManagerRegistry $managerRegistry)
    {
        
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);  
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {
           
            $address->setUser($this->getUser());
            $address->setFullAddress($address->getAddress(). ' ' .$address->getPostalCode(). ' ' .$address->getCity(). ' ' .$address->getCountry());
            $manager = $managerRegistry->getManager();
            $manager->persist($address);
            $manager->flush();

            $this->addFlash('success', 'L\'addresse a bien ??t?? ajout??e');
            return $this->redirectToRoute('profil_address_liste');

        }
        return $this->render('profil/address/addressForm.html.twig', [
            'addressForm' => $form->createView()
        ]);
    }

    #[Route('/profil/address/update/{id}', name: 'profil_address_update')]
    public function updateUserAddress(AddressRepository $addressRepository, int $id , Request $request, ManagerRegistry $managerRegistry)
    {
        $address = $addressRepository->find($id);
        $form = $this->createForm(AddressType::class, $address);  
        $form->handleRequest($request); 
        

        
        if ($address->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }


        if ($form->isSubmitted() && $form->isValid()) {
                
           

            $manager = $managerRegistry->getManager();
            $manager->persist($address);
            $manager->flush();

            $this->addFlash('success', 'L\'addresse a bien ??t?? Modifier');
            return $this->redirectToRoute('profil_address_liste');

        }
        return $this->render('profil/address/addressForm.html.twig', [
            'addressForm' => $form->createView()
        ]);
    }

    #[Route('/profil/address/delete/{id}', name: 'profil_address_delete')]
     public function deleteUserAddress(
         AddressRepository $addressRepository,
         int $id,
         ManagerRegistry $managerRegistry
     ) {
         $address = $addressRepository->find($id);  //r??cuperer l'address ?? suprimer en bdd

         $manager = $managerRegistry->getManager();
         $manager->remove($address);
         $manager->flush();
         $this->addFlash('success', 'l\'addresse ?? ??t?? bien suprim??e'); 
         return $this->redirectToRoute('profil_address_liste');
     }     


     #[Route('/admin/address', name: 'admin_address_index')]
     public function adminIndex(AddressRepository $addressRepository): Response
     {
         $address = $addressRepository->findAll();
         return $this->render('admin/address.html.twig', [
             'address' => $address,
         ]);
     }

     

    // #[Route('/admin/addresse/create', name: 'address_create')]
    // public function create(Request $request, ManagerRegistry $managerRegistry)
    // {
    //     $address = new Address();
    //     $form = $this->createForm(AddressType::class, $address);  
    //     $form->handleRequest($request); 

    //     if ($form->isSubmitted() && $form->isValid()) {


    //         $manager = $managerRegistry->getManager();
    //         $manager->persist($address);
    //         $manager->flush();

    //         $this->addFlash('success', 'L\'addresse a bien ??t?? ajout??e');
    //         return $this->redirectToRoute('admin_address_index');

    //     }
    //     return $this->render('admin/addressForm.html.twig', [
    //         'addressForm' => $form->createView()
    //     ]);
    // }

     #[Route('/admin/address/delete/{id}', name: 'address_delete')]
     public function delete(
         AddressRepository $addressRepository,
         int $id,
         ManagerRegistry $managerRegistry
     ) {
         $address = $addressRepository->find($id);  //r??cuperer l'address ?? suprimer en bdd

         $manager = $managerRegistry->getManager();
         $manager->remove($address);
         $manager->flush();
         $this->addFlash('success', 'l\'addresse ?? ??t?? bien suprim??e'); 
         return $this->redirectToRoute('admin_address_index');
     }     
}
