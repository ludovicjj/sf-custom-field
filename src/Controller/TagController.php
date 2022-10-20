<?php

namespace App\Controller;

use App\Repository\TagRepository;
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
}