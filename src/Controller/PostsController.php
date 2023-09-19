<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostsController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }

    #[Route('/posts', name: 'app_posts')]
    public function index(): Response
    {
        $repository = $this->em->getRepository(Post::class);
        $posts = $repository->findAll();

       return $this->render('posts/index.html.twig', array(
        'posts' => $posts
       ));
    }
}
