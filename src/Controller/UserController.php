<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserType;
use App\Form\ProfilType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/profil', name: 'user_index')]
    public function index(UserRepository $userRepository, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $user = $userRepository->find(['id'=>$this->getUser()]);
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){

            $manager = $managerRegistry->getManager();
            $manager->persist($user);
            $manager->flush();

        }

        return $this->render('profil/index.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    #[Route('/profil/liste', name: 'profil_liste')]
    public function userListe(UserRepository $userRepository)
    {
        $listeUser = $userRepository->findBy(['id'=>$this->getUser()]);
    
        return $this->render('profil/profil.html.twig', [
            
            'user' => $listeUser
        ]);
    }

    #[Route('profil/user/create', name: 'profil_user_create')]
    public function createUserAddress(Request $request, ManagerRegistry $managerRegistry): Response
    {
        $user = new User();
        $form = $this->createForm(ProfilType::class, $user);  
        $form->handleRequest($request); 

        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $managerRegistry->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'L\'utilisateur a bien été ajoutée');
            return $this->redirectToRoute('profil_liste');

        }
        return $this->render('profil/userForm.html.twig', [
            'userForm' => $form->createView()
        ]);
    }

    #[Route('/profil/user/update/{id}', name: 'profil_user_update')]
    public function updateUser(UserRepository $userRepository, int $id, Request $request, ManagerRegistry $managerRegistry,UserPasswordHasherInterface $userPasswordHasher):Response
    {
        // $user = $userRepository->find($id); //récuperer l'id  du user 
        // // $form = $this->createForm(UserType::class, $user);
        // $form = $this->createForm(RegistrationFormType::class, $user);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {

            $user = $userRepository->find($id);
            $form = $this->createForm(ProfilType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {


                if($form->get('plainPassword')->getData() == null) {
                    $user->setPassword($user->getPassword());
                }else{
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
            );
            }
                $user->setIsVerified(true);
                
                $manager = $managerRegistry->getManager();
                $manager->persist($user);
                $manager->flush();
        // }

            $this->addFlash('success', 'Le profile a bien été modifiée');
            return $this->redirectToRoute('profil_liste');
        }

        return $this->render('profil/userForm.html.twig', [
            'userForm' => $form->createView(),

        ]);
    }

    #[Route('/profil/user/delete/{id}', name: 'profil_user_delete')]
    public function deleteUser(UserRepository $userRepository, ManagerRegistry $managerRegistry):Response  
     {
        $user = $userRepository->find($this->getUser()); // récuperer le user à suprimer en bdd
        
        $manager = $managerRegistry->getManager();
        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'le profile à été bien suprimée');
        return $this->redirectToRoute('home');
    }

    #[Route('/admin/user', name: 'admin_user_index')]
    public function adminIndex(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    // #[Route('/admin/users/create', name: 'user_create')]
    // public function create(Request $request, ManagerRegistry $managerRegistry)
    // {
    //     $user = new User();
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $manager = $managerRegistry->getManager();
    //         $manager->persist($user);
    //         $manager->flush();

    //         $this->addFlash('success', 'Le profile  a bien été ajoutée');
    //         return $this->redirectToRoute('admin_user_index');
    //     }
    //     return $this->render('admin/userForm.html.twig', [
    //         'userForm' => $form->createView()
    //     ]);
    // }

    // #[Route('/admin/user/update/{id}', name: 'user_update')]
    // public function update(UserRepository $userRepository, int $id, Request $request, ManagerRegistry $managerRegistry)
    // {
    //     $user = $userRepository->find($id); //récuperer l'id et du user 
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $manager = $managerRegistry->getManager();
    //         $manager->persist($user);
    //         $manager->flush();

    //         $this->addFlash('success', 'Le profile a bien été modifiée');
    //         return $this->redirectToRoute('admin_user_index');
    //     }

    //     return $this->render('admin/userForm.html.twig', [
    //         'userForm' => $form->createView(),

    //     ]);
    // }
    #[Route('/admin/user/delete/{id}', name: 'user_delete')]
    public function delete(
        UserRepository $userRepository,
        int $id,
        ManagerRegistry $managerRegistry
    ) {
        $user = $userRepository->find($id); // récuperer le user à suprimer en bdd

        $manager = $managerRegistry->getManager();
        $manager->remove($user);
        $manager->flush();
        $this->addFlash('success', 'le profile à été bien suprimée');
        return $this->redirectToRoute('admin_user_index');
    }
}
