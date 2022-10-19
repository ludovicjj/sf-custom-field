<?php

namespace App\Controller;

use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    #[Route('/api/tags', name: 'api_tag_search')]
    public function search(Request $request, TagRepository $tagRepository)
    {
        $tags = $tagRepository->search($request->query->get('name'));
    }
}