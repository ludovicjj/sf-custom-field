<?php

namespace App\Controller;

use App\Entity\Contributor;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/', name: 'app_blog_home')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('posts/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/new/data')]
    public function new(EntityManagerInterface $entityManager): Response
    {
        // Will be replaced by fixtures soon
        $user = new Contributor();
        $user
            ->setFirstname('John')
            ->setLastname('Doe')
            ->setEmail('johndoe@email.com')
            ->setPassword("123456") // use UserPasswordHasherInterface to hash plainPassword
        ;
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response(sprintf(
            'Contributor %d created with email %s',
            $user->getId(),
            $user->getEmail()
        ));
    }
}