<?php

namespace App\Controller;

use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/register', name: 'app_blog_register')]
    public function register(Request $request): Response
    {
        $form = $this->createForm(RegistrationType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clickedBtn = $form->getClickedButton();
            if ($clickedBtn && $clickedBtn->getName() === "SaveAndAdd") {
                if ($form->get('password')->getData() === null) {
                    $form->get('password')->addError(new FormError("Password is required"));
                } else {
                    $this->addFlash('success', 'Register with success');
                    return $this->redirectToRoute('app_blog_register');
                }
            }
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}