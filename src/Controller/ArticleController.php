<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\ArticleType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ArticleController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UrlGeneratorInterface $urlGenerator
    ){}

    #[Route('/article', name: 'app_article')]
    public function index(PostRepository $postRepository): Response
    {
        $articles = $postRepository->findAllPostsJoinTags();

        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/article/new', name: 'app_article_new')]
    public function new(Request $request): Response
    {
        $article = new Post();

        $form = $this->createForm(ArticleType::class, $article, [
            'search' => $this->urlGenerator->generate('api_tag_create')
        ])
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $this->entityManager->persist($article);
            $this->entityManager->flush();
            $this->addFlash('success', 'Post created with success');
            return $this->redirectToRoute('app_article');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/article/update/{id}', name: 'app_article_update')]
    public function update(int $id, Request $request, PostRepository $postRepository): Response
    {
        $article = $postRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(ArticleType::class, $article, [
            'search' => $this->urlGenerator->generate('api_tag_create')
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Post updated with success');
            return $this->redirectToRoute('app_article');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}