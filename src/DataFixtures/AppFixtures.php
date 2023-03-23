<?php

namespace App\DataFixtures;

use App\Factory\CommentFactory;
use App\Factory\ProductFactory;
use App\Factory\TagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        TagFactory::createMany(5);
        ProductFactory::createMany(20, [
           'comments' => CommentFactory::new()->many(0,10),
           'tags' => TagFactory::randomRange(2,5),
        ]);
    }
}
