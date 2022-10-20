<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/posts/{id<\d+>}', name: 'app_blog_edit')]
    public function edit(PostRepository $postRepository, Request $request, int $id, EntityManagerInterface $entityManager)
    {
        $post = $postRepository->find($id);
        $form = $this->createForm(PostType::class, $post)->handleRequest($request);

        if ($form->isSubmitted()) {
            $post = $form->getData();
            $form->get('tags')->getData()->map(fn($tag) => $post->addTag($tag));
            $post->setTitle($form->get('title')->getData());
            $post->setContent($form->get('content')->getData());
            $entityManager->flush();
            $this->addFlash('success', "Post updated with success !");
        }

        return $this->render('posts/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}