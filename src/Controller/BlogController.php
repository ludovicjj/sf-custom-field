<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
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
    public function edit(PostRepository $postRepository, Request $request, int $id)
    {
        $post = $postRepository->find($id);
        $form = $this->createForm(PostType::class, $post)->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var Post $post */
            $post = $form->getData();
            dump('Post Data : ', $post);
            foreach ($post->getTags() as $tag) {
                dump('Tag', $tag->getName());
            }
            die('stop');
        }

        return $this->render('posts/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}