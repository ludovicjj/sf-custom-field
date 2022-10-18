<?php

namespace App\DataFixtures;

use App\Factory\PostFactory;
use App\Factory\TagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Pre-create Tags
        TagFactory::createMany(10);

        PostFactory::new()
            ->many(5) // Create 5 posts
            ->create(function() {
                // Each post uses between 0 and 5 random tags from those already in the database
                return ['tags' => TagFactory::randomRange(1, 5)];
            });

        $manager->flush();
    }
}
