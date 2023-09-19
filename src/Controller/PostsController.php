<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostsController extends AbstractController
{
    #[Route('/posts', name: 'app_posts')]
    public function index(): Response
    {
       return $this->render('base.html.twig');
    }
}
