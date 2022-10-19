<?php

namespace App\Controller;

use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
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

    #[Route('/posts/{id}', name: 'app_blog_edit')]
    public function edit(PostRepository $postRepository, int $id)
    {
        $post = $postRepository->find($id);
        $form = $this->createForm(PostType::class, $post);

        return $this->render('posts/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}