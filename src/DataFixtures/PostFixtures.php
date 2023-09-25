<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $post = new Post();
        $post->setTitle('Pierwszy post');
        $post->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer sed turpis feugiat lectus imperdiet mattis. Suspendisse sed maximus nulla. Proin sed dapibus dolor.');
        $post->setImagePath('https://cdn.pixabay.com/photo/2023/06/11/16/07/switzerland-8056381_1280.jpg');
        
        $manager->persist($post);
        $manager->flush();
    }
}
