<?php

namespace App\Controller;

use App\Entity\Medecin;
use App\Form\MedecinType;
use App\Repository\MedecinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MedecinController extends AbstractController
{
    public function __construct(private MedecinRepository $medecinRepository)
    {
    }

    #[Route('/medecin', name: 'app_medecin_index')]
    public function index(): Response
    {
        $medecins = $this->medecinRepository->findAll();

        return $this->render('medecin/index.html.twig', [
            'medecins' => $medecins
        ]);
    }


    #[Route('/medecin/new', name: 'app_medecin_new')]
    public function create(Request $request, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): Response
    {
        $medecin = new Medecin();
        $form = $this->createForm(MedecinType::class, $medecin, [
            'action' => $urlGenerator->generate('app_medecin_new')
        ])->handleRequest($request);

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
    public function update(Request $request, int $id, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): Response
    {
        $medecin = $this->medecinRepository->find($id);

        if (!$medecin) {
            $this->createNotFoundException();
        }

        $form = $this->createForm(MedecinType::class, $medecin, [
            'action' => $urlGenerator->generate('app_medecin_update', [
                'id' => $id
            ])
        ])->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Medecin updated with success');

            return $this->redirectToRoute('app_medecin_index');
        }
        return $this->render('medecin/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}