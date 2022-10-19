<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagToArrayTransformer implements DataTransformerInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Transforms an object (Collection) to an array.
     * @param Collection $value
     * @return array
     */
    public function transform(mixed $value): array
    {
        if ($value->count() > 0) {
            return $value->map(fn($tag) => (string)$tag->getId())->toArray();
        }

        return [];
    }

    /**
     * Transforms an array to an object (Collection).
     * @param array $value
     * @return Collection
     */
    public function reverseTransform(mixed $value): Collection
    {
        if (empty($value)) {
            return new ArrayCollection([]);
        }

        $tags = $this->entityManager->getRepository(Tag::class)->findBy(['id' => $value]);

        return new ArrayCollection($tags);
    }
}