<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAllPublished();

        return $this->render('post/index.html.twig', compact('posts'));
    }

    #[Route('/post/{slug}', name: 'app_post_show')]
    public function show(Post $post): Response
    {
        return $this->render('post/show.html.twig', compact('post'));
    }
}
