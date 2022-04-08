<?php

namespace App\Controller;

use App\Entity\Machine;
use App\Form\MachineType;
use App\Repository\MachineRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MachineController extends AbstractController
{
    #[Route('/machines', name: 'machine_index')]
    public function index(MachineRepository $machineRepository): Response
    {
        $machines = $machineRepository->findAll();
        return $this->render('machine/index.html.twig', [
            'machines' => $machines,
        ]);
    }

    #[Route('/machines/marque/{marque}', name: 'machine_index_marque')]
    public function indexMarque(MachineRepository $machineRepository, string $marque): Response
    {
        $machines = $machineRepository->findBy(['name' => $marque]);
        return $this->render('machine/marque.html.twig', [
            'machine' => $machines
        ]);
    }

    #[Route('/machine/{id}', name: 'machine_show')]
    public function show(MachineRepository $machineRepository, int $id): Response
    {
        $machines = $machineRepository->findBy(['id' => $id]);
        return $this->render('machine/show.html.twig', [
            'machine' => $machines
        ]);
    }

    #[Route('/admin/machine', name: 'admin_machine_index')]
    public function adminIndex(MachineRepository $machineRepository): Response
    {
        $machines = $machineRepository->findAll();
        return $this->render('admin/machines.html.twig', [
            'machines' => $machines
        ]);
    }

    #[Route('/admin/machines/create', name: 'machine_create')]
    public function create(Request $request, ManagerRegistry $managerRegistry)
    {
        $machine = new Machine();
        $form = $this->createForm(MachineType::class, $machine); // creation d'un formulaire avec en parametre la nouvelle  maison 
        $form->handleRequest($request); // gestionnaire de requetes HTTP

        if ($form->isSubmitted() && $form->isValid()) {

            $infoImg = $form['img']->getData(); //récuperer les info de l'image
            $extentionImg = $infoImg->guessExtension(); //récuperer l'extention e l'image
            $nomImg = time() . '-1.' . $extentionImg; //créer un nom unique pour l'image 
            $infoImg->move($this->getParameter('dossier_photos_machine'), $nomImg); //télécharger l'image dans le dossier adéquat
            $machine->setImg($nomImg); //définit le nom de l'image à mettre en bdd

            $manager = $managerRegistry->getManager();
            $manager->persist($machine);
            $manager->flush();

            $this->addFlash('success', 'la machine a bien été ajouter'); // message de succes

            return $this->redirectToRoute('admin_machine_index');
        }

        return $this->render(
            'admin/machineForm.html.twig',
            [
                'machineForm' => $form->createView()
            ]
        );
    }

    #[Route('/admin/machine/update/{id}', name: 'machine_update')]
    public function update(MachineRepository $machineRepository, int $id, Request $request, ManagerRegistry $managerRegistry)
    {
        $machine = $machineRepository->find($id); //récuperer l'id et du coup la machine 
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $infoImg = $form['img']->getData();
            $nomOldImg = $machine->getImg();
            if ($infoImg !== null) {
                $cheminOldImg = $this->getParameter('dossier_photos_machine') . '/' . $nomOldImg;
                if (file_exists($cheminOldImg)) {
                    unlink($cheminOldImg);
                }
                $extensionImg = $infoImg->guessExtension();
                $nomImg = time() . '-1.' . $extensionImg;
                $infoImg->move($this->getParameter('dossier_photos_machine'), $nomImg);
                $machine->setImg($nomImg);
            } else {
                $machine->setImg($nomOldImg);
            }
            $manager = $managerRegistry->getManager();
            $manager->persist($machine);
            $manager->flush();
            $this->addFlash('success', 'La machine a bien été modifiée');
            return $this->redirectToRoute('admin_machine_index');
        }

        return $this->render('admin/machineForm.html.twig', [
            'machineForm' => $form->createView()
            /*'machine' => $machine*/
        ]);
    }

    #[Route('/admin/machine/delete/{id}', name: 'machine_delete')]
    public function delete(
        MachineRepository $machineRepository,
        int $id,
        ManagerRegistry $managerRegistry
    ) {
        $machine = $machineRepository->find($id); // récuperer la machine à suprimer en bdd
        $nomImg = $machine->getImg();
        if ($nomImg !== null) {
            $chemainImg = $this->getParameter('dossier_photos_machine') . '/' . $nomImg; //reconstituer  le chemain de l'image
            if (file_exists($chemainImg)) { //verifier si le fichier existe
                unlink($chemainImg); // suprimer les images 
            }
        }
        $manager = $managerRegistry->getManager();
        $manager->remove($machine);
        $manager->flush();
        $this->addFlash('success', 'la machine à été bien suprimée'); // message de succés 
        return $this->redirectToRoute('admin_machine_index');
    }
}