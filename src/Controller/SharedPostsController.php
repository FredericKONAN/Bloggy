<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\SharePostType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;

class SharedPostsController extends AbstractController
{

    #[Route(
        '/post/{slug}/partage',
        name: 'app_post_share',
        requirements: [
//            'date' => Requirement::DATE_YMD,
            'slug'=> Requirement::ASCII_SLUG,
        ],
        methods: ['GET', 'POST'],
    )]
//    #[Entity('post',expr: 'repository.findOneByPublishedDateAnSlug(date, slug)')]
    public function sharePost(Request $request, MailerInterface $mailer, Post $post):Response
    {


//        $post = $this->postRepository->findOneByPublishedDateAnSlug($date,$slug);
        $form = $this->createForm(SharePostType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $data=  $form->getData();

//            $postUrl = $this->generateUrl(
//                'app_post_show',
//                $post->getPathParams(),
//                UrlGeneratorInterface::ABSOLUTE_URL
//            );

            $sujet = sprintf('%s vous recommande de lire "%s"', $data['nom_expediteur'], $post->getTitre());
//           $message = sprintf(
//
//               "Lit \"%s\" a l'adresse suivant %s.\n\n voici le commentaire de %s < %s >",
//               $post->getTitre(),
//               $postUrl,
//               $data['nom_expediteur'],
//               $data['commentaires'],
//
//           );

            $mail =  (new TemplatedEmail())
                ->from(
                    new Address(
                        $this->getParameter('app.contact.email'),
                        $this->getParameter('app.name'),
                    ))

                ->to($data['mail_destinateur'])
                ->subject($sujet)
                ->htmlTemplate('email/shared_post/create.html.twig')
                ->context([
                    'nom_expediteur' => $data['nom_expediteur'],
                    'post' => $post,
                    'commentaire'=> $data['commentaires'],
                ])
            ;

            $mailer->send($mail);

            $this->addFlash('success', "l'article a ete partage avec succes avec votre amie.");

            return $this->redirectToRoute('app_home');

        }

        return $this->render('shared_post/create.html.twig', compact('form', 'post'));
    }
}
