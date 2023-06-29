<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        $user = new Utilisateur();
        $user->setNom('Super Dev');
        $user->setEmail('superdev@gmail.com');
        $user->setPassword('$2y$13$H.j2pwe4poYsXHehCDvZ/eO53wtkT5d12CyPI.WeT2MDIlgZPjqG6');

        $utilisateurRepository->save($user, true);

        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
        ]);
    }
}
