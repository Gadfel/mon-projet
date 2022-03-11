<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ProfilType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/profil', name: 'user_index')]
    public function index(UserRepository $userRepository, Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $managerRegistry): Response
    {
        $user = $userRepository->find($this->getUser());
        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setIsVerified(true);

                $user->setPassword(
                $userPasswordHasher->hashPassword(
                 $user,
                $form->get('plainPassword')->getData()
                        )
                    );
            $manager = $managerRegistry->getManager();
            $manager->persist($user);
            $manager->flush();
        }

        return $this->render('profil/index.html.twig', [
            'profilForm' => $form->createView()
        ]);
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
    // public function create(Request $request, , ManagerRegistry $managerRegistry)
    // {
    //     $user = new User();
    //     $form = $this->createForm(UserType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //        

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

    #[Route('/admin/user/update/{id}', name: 'user_update')]
    public function update(UserRepository $userRepository, int $id, Request $request, ManagerRegistry $managerRegistry)
    {
        $user = $userRepository->find($id); //récuperer l'id et du user 
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $manager = $managerRegistry->getManager();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', 'Le profile a bien été modifiée');
            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/userForm.html.twig', [
            'userForm' => $form->createView(),

        ]);
    }
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
