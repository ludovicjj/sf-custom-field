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
        // create 100 Tag using a sequence callback with an incremental index
        TagFactory::createSequence(
            function() {
                foreach (range(1, 100) as $i) {
                    yield ['name' => "tag-$i"];
                }
            }
        );

        PostFactory::new()
            ->many(5) // Create 5 posts
            ->create(function() {
                // Each post uses between 0 and 5 random tags from those already in the database
                return ['tags' => TagFactory::randomRange(1, 5)];
            });

        $manager->flush();
    }
}
