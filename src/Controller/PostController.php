<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\SharePostType;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class PostController extends AbstractController
{
    public function __construct(private PostRepository $postRepository){}

    #[Route('/', name: 'app_accueil', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $query = $this->postRepository->getAllPublishedOrderedQuery();
        $numeroDePage  = $request->query->getInt('page', 1);


        $pagination = $paginator->paginate($query, $numeroDePage, Post::NBRE_ELEMENTS_PAR_PAGE, [
            PaginatorInterface::PAGE_OUT_OF_RANGE=> 'fix',
        ]);

        return $this->render('post/index.html.twig', compact('pagination'));
    }

    #[Route(
        '/post/{date}/{slug}',
        name: 'app_post_show',
        requirements: [
            'date' => Requirement::DATE_YMD,
            'slug'=> Requirement::ASCII_SLUG,
        ],
        methods: ['GET'],
    )]
    public function show(string $date, string $slug): Response
    {
        $post = $this->postRepository->findOneByPublishedDateAnSlug($date,$slug);

        if (is_null($post)){
            throw $this->createNotFoundException('Post not found');
        }

        return $this->render('post/show.html.twig', compact('post'));
    }

    #[Route(
        '/post/{date}/{slug}/partage',
        name: 'app_post_share',
        requirements: [
            'date' => Requirement::DATE_YMD,
            'slug'=> Requirement::ASCII_SLUG,
        ],
        methods: ['GET', 'POST'],
    )]
    public function sharePost(Request $request,string $date, string $slug){

        $form = $this->createForm(SharePostType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
           dd( $form->getData());
        }
//        dd($date, $slug);

        return $this->render('post/share.html.twig', compact('form'));
    }

}
