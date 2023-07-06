<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    public function __construct(private PostRepository $postRepository){}

    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        $posts = $this->postRepository->findAllPublishedOrdered();

        return $this->render('post/index.html.twig', compact('posts'));
    }

    #[Route('/post/{year}/{month}/{day}/{slug}', name: 'app_post_show')]
    public function show(int $year, int $month, int $day, string $slug): Response
    {
        $post = $this->postRepository->findOneByPublishedDateAnSlug($year, $month,$day,$slug);

        if (is_null($post)){
            throw $this->createNotFoundException('Post not found');
        }

        return $this->render('post/show.html.twig', compact('post'));
    }

}
