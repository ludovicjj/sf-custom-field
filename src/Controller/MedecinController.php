<?php

namespace App\Controller;

use App\Form\MedecinType;
use App\Repository\MedecinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedecinController extends AbstractController
{
    #[Route('/medecin', name: 'app_medecin_index')]
    public function index(MedecinRepository $medecinRepository): Response
    {
        $medecins = $medecinRepository->findAll();

        return $this->render('medecin/index.html.twig', [
            'medecins' => $medecins
        ]);
    }


    #[Route('/medecin/new', name: 'app_medecin_new')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MedecinType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $medecin = $form->getData();
            $entityManager->persist($medecin);
            $entityManager->flush();

            $this->addFlash('success', 'Medecin added to the list');

            return $this->redirectToRoute('app_medecin_index');
        }
        return $this->render('medecin/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/medecin/update/{id}', name: 'app_medecin_update')]
    public function update(): Response
    {
        return new Response('TODO update');
    }
}