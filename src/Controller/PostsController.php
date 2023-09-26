<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostFormType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use DateTimeImmutable;

class PostsController extends AbstractController
{
    private $postRepository;
    private $em;

    public function __construct(PostRepository $postRepository, EntityManagerInterface $em)
    {
        $this->postRepository = $postRepository;
        $this->em = $em;
    }

    #[Route('/posts', name: 'app_posts_index', methods: ['GET'])]
    public function index(): Response
    {
        $posts = $this->postRepository->findAll();

        return $this->render('posts/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/posts/create', name: 'app_posts_create')]
    public function create(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newPost = $form->getData();

            $newPost->setCreatedAt(DateTimeImmutable::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));

            $imagePath = $form->get('imagePath')->getData();

            if ($imagePath) {
                $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                try {
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads',
                        $newFileName
                    );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                $newPost->setImagePath('/uploads/' . $newFileName);
            }


            $this->em->persist($newPost);
            $this->em->flush();

            return $this->redirectToRoute('app_posts_index');
        }

        return $this->render('posts/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/posts/edit/{id}', name: 'app_posts_edit', methods: ['POST', 'GET'])]
    public function edit($id, Request $request): Response
    {
        $post = $this->postRepository->find($id);
        $form = $this->createForm(PostFormType::class, $post);

        $form->handleRequest($request);
        $imagePath = $form->get('imagePath')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($imagePath) {
                // new image -> upload new photo
                if ($post->getImagePath() !== null) {

                    // $this->getParameter('kernel.project_dir') . $post->getImagePath()

                    $newFileName = uniqid() . '.' . $imagePath->guessExtension();

                    try {
                        $imagePath->move(
                            $this->getParameter('kernel.project_dir') . '/public/uploads',
                            $newFileName
                        );
                    } catch (FileException $e) {
                        return new Response($e->getMessage());
                    }

                    $post->setImagePath('/uploads/' . $newFileName);

                    $this->em->flush();

                    return $this->redirectToRoute('app_posts_index');
                }
            } else {
                // no new image
                $post->setTitle($form->get('title')->getData());
                $post->setDescription($form->get('description')->getData());

                $this->em->flush();

                return $this->redirectToRoute('app_posts_index');
            }
        }

        return $this->render('posts/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    #[Route('/posts/delete/{id}', name:'app_posts_delete', methods:['GET', 'DELETE'])]
    public function delete($id): Response
    {
        $post = $this->postRepository->find($id);
        $this->em->remove($post);

        $this->em->flush();

        return $this->redirectToRoute('app_posts_index');
    }


    #[Route('/posts/{id}', name: 'app_posts_show', methods: ['GET'])]
    public function show($id): Response
    {

        $post = $this->postRepository->find($id);

        return $this->render('posts/show.html.twig', [
            'post' => $post
        ]);
    }
}
