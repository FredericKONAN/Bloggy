<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractDashboardController
{

    public function __construct( private AdminUrlGenerator $adminUrlGenerator){}


    #[IsGranted('ROLE_ADMIN')]
    #[Route('%app.admin_path%', name: 'admin')]
    public function index(): Response
    {

         return $this->redirect($this->adminUrlGenerator->setController(PostCrudController::class)->generateUrl());

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Visiter le site Web public', 'fa fa-home', '/');
        yield MenuItem::section('Blog', );
         yield MenuItem::linkToCrud('Posts', 'fa fa-file-text', Post::class);


        yield MenuItem::section('Utilisateurs', );
        yield MenuItem::linkToCrud('Utilisateur', 'fa fa-user', Utilisateur::class);
    }
}
