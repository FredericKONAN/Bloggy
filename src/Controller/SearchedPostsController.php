<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchedPostsController extends AbstractController
{
    #[Route('/searched/posts', name: 'app_searched_posts')]
    public function index(): Response
    {
        return $this->render('searched_posts/index.html.twig', [
            'controller_name' => 'SearchedPostsController',
        ]);
    }
}
