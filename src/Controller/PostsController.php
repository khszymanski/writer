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
    private $postRepository;

    public function __construct(PostRepository $postRepository){
        $this->postRepository = $postRepository;
    }

    #[Route('/posts', name: 'app_posts_index', methods: ['GET'])]
    public function index(): Response
    {
        $posts = $this->postRepository->findAll();

       return $this->render('posts/index.html.twig', [
        'posts' => $posts
       ]);
    }
    
    #[Route('/posts/create', name:'app_posts_create')]
    public function create(){

        return $this->render('posts/create.html.twig');
    }

    #[Route('/posts/{id}', name: 'app_posts_show', methods: ['GET'])]
    public function show($id): Response{

        $post = $this->postRepository->find($id);

        return $this->render('posts/show.html.twig', [
            'post' => $post
        ]);
    }

}
