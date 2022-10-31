<?php

namespace App\Controller;

use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'app_post')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAllPostsJoinTags();
        return $this->render('posts/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/posts/{id<\d+>}', name: 'app_post_edit')]
    public function edit(
        PostRepository $postRepository,
        Request $request,
        int $id,
        EntityManagerInterface $entityManager
    ): Response
    {
        $post = $postRepository->find($id);
        $form = $this->createForm(PostType::class, $post)->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager->flush();
            $this->addFlash('success', "Post updated with success !");

            return $this->redirectToRoute('app_post');
        }

        return $this->render('posts/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}