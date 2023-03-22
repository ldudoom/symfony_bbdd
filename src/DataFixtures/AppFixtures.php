<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Metadata;
use App\Entity\Product;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product();
        $product->setName('Producto de Prueba');
        $product->setSummary('Resumen de Prueba');

        $metadata = new Metadata();
        $metadata->setCode(rand(100,200));
        $metadata->setContent('Contenido oficial del producto');

        $manager->persist($metadata);

        $product->setMetadata($metadata);

        $manager->persist($product);

        $tag1 = new Tag();
        $tag1->setName('Etiqueta #1');
        $manager->persist($tag1);

        $tag2 = new Tag();
        $tag2->setName('Etiqueta #2');
        $manager->persist($tag2);

        $product->addTag($tag1);
        $product->addTag($tag2);

        $comment1 = new Comment();
        $comment1->setContent('Comentario #1');
        $manager->persist($comment1);

        $comment2 = new Comment();
        $comment2->setContent('Comentario #2');
        $manager->persist($comment2);

        $product->addComment($comment1);
        $product->addComment($comment2);

        $manager->flush();
    }
}
