<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class TagController extends AbstractController
{
    #[Route('/api/tags', name: 'api_tag_search')]
    public function search(
        Request $request,
        TagRepository $tagRepository,
        SerializerInterface $serializer,
    ): Response
    {
        $tags = $tagRepository->search($request->query->get('name', ''));
        $json = $serializer->serialize(
            $tags,
            'json',
            [AbstractNormalizer::IGNORED_ATTRIBUTES => ['posts']]
        );

        return new JsonResponse($json, 200, [], true);
    }

    #[Route('/api/tags/create', name: 'api_tag_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, TagRepository $tagRepository): Response
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? null;
        $tag = $tagRepository->findOneBy(['name' => $name]);

        // If tag not exist into DB, create new tag, persist/flush it
        if (!$tag) {
            $tag = new Tag();
            $tag->setName($data['name']);
            $entityManager->persist($tag);
            $entityManager->flush();
        }

        // Then return json with the new tag or the already exist tag
        return $this->json(['id' => $tag->getId(), 'name' => $tag->getName()]);
    }
}