<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    public function __construct(private PostRepository $postRepository){}

    #[Route('/', name: 'app_accueil', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $query = $this->postRepository->getAllPublishedOrderedQuery();
        $numeroDePage  = $request->query->getInt('page', 1);

        $pagination = $paginator->paginate($query, $numeroDePage, 3);

        return $this->render('post/index.html.twig', compact('pagination'));
    }

    #[Route(
        '/post/{year}/{month}/{day}/{slug}',
        name: 'app_post_show',
        requirements: [
            'year' => '[0-9]{4}',
            'month'=> '[0-9]{2}',
            'day'=> '[0-9]{2}',
            'slug'=> '[a-z0-9\-]+',
        ],
        methods: ['GET'],
    )]
    public function show(int $year, int $month, int $day, string $slug): Response
    {
        $post = $this->postRepository->findOneByPublishedDateAnSlug($year, $month,$day,$slug);

        if (is_null($post)){
            throw $this->createNotFoundException('Post not found');
        }

        return $this->render('post/show.html.twig', compact('post'));
    }

}
