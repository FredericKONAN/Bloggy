<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentsRepository;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
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
    #[Route(
        '/tags/{slug}', name: 'app_post_by_tag',
        requirements: [
            'slug'=> Requirement::ASCII_SLUG,
        ],
        methods: ['GET']
    )]
    public function index(PaginatorInterface $paginator, Request $request, ?string $slug, TagRepository $tagRepository): Response
    {
        $tag = null;

        if($slug){
            $tag =  $tagRepository->findOneBySlug($slug);

        }
//        if($slugTag){
//            dd('Post filtre par tag');
//        }else{
//            dd('Afficher tout');
//        }
        $query = $this->postRepository->getAllPublishedOrderedQuery($tag);
        $numeroDePage  = $request->query->getInt('page', 1);


        $pagination = $paginator->paginate($query, $numeroDePage, Post::NBRE_ELEMENTS_PAR_PAGE, [
            PaginatorInterface::PAGE_OUT_OF_RANGE=> 'fix',
        ]);

        return $this->render('post/index.html.twig', [
            'pagination' => $pagination,
            'tagName' => $tag?->getName()
        ]);
    }

    #[Route(
        '/post/{slug}',
        name: 'app_post_show',
        requirements: [
//            'date' => Requirement::DATE_YMD,
            'slug'=> Requirement::ASCII_SLUG,
        ],
        methods: ['GET', 'POST'],
    )]
//    #[Entity('post',expr: 'repository.findOneByPublishedDateAnSlug(date, slug)')]
    public function show(Request $request,  CommentsRepository $commentsRepo  ,Post $post): Response
    {
//        $criteria = Criteria::create()
//            ->andWhere(Criteria::expr()->eq('isActive', true))
//            ->orderBy(['createdAt' => 'ASC'])
//        ;
//        $comments =$post->getComments()->matching($criteria);

        $similarPostByTag = $this->postRepository->findSimilar($post);


        $comments = $post->getActiveComments($post);

        $commentForm = $this->createForm(CommentType::class);

        $commentForm->handleRequest($request);

    if($commentForm->isSubmitted() && $commentForm->isValid()){

       $comment = $commentForm->getData();
        $comment->setPost($post);

        $commentsRepo->save($comment, flush: true);

        $this->addFlash('success', "Commentaire ajoute avec succes!");

        return $this->redirectToRoute('app_post_show', ['slug'=> $post->getSlug()]);

       //        dd($comment = $commentForm->getData());
    }
//        $post = $this->postRepository->findOneByPublishedDateAnSlug($date,$slug);
//
//        if (is_null($post)){
//            throw $this->createNotFoundException('Post not found');
//        }
        return $this->render('post/show.html.twig', compact('post', 'comments', 'commentForm', 'similarPostByTag'));
    }

//    #[Route(
//        '/post/{slug}/partage',
//        name: 'app_post_share',
//        requirements: [
////            'date' => Requirement::DATE_YMD,
//            'slug'=> Requirement::ASCII_SLUG,
//        ],
//        methods: ['GET', 'POST'],
//    )]
//    #[Entity('post',expr: 'repository.findOneByPublishedDateAnSlug(date, slug)')]
//    public function sharePost(Request $request, MailerInterface $mailer, Post $post){
//
//
////        $post = $this->postRepository->findOneByPublishedDateAnSlug($date,$slug);
//        $form = $this->createForm(SharePostType::class);
//
//        $form->handleRequest($request);
//
//        if($form->isSubmitted() && $form->isValid())
//        {
//           $data=  $form->getData();
//
////            $postUrl = $this->generateUrl(
////                'app_post_show',
////                $post->getPathParams(),
////                UrlGeneratorInterface::ABSOLUTE_URL
////            );
//
//           $sujet = sprintf('%s vous recommande de lire "%s"', $data['nom_expediteur'], $post->getTitre());
////           $message = sprintf(
////
////               "Lit \"%s\" a l'adresse suivant %s.\n\n voici le commentaire de %s < %s >",
////               $post->getTitre(),
////               $postUrl,
////               $data['nom_expediteur'],
////               $data['commentaires'],
////
////           );
//
//            $mail =  (new TemplatedEmail())
//                ->from(
//                    new Address(
//                        $this->getParameter('app.contact.email'),
//                        $this->getParameter('app.name'),
//                    ))
//
//                ->to($data['mail_destinateur'])
//                ->subject($sujet)
//                ->htmlTemplate('email/post/share.html.twig')
//                ->context([
//                    'nom_expediteur' => $data['nom_expediteur'],
//                    'post' => $post,
//                    'commentaire'=> $data['commentaires'],
//                ])
//            ;
//
//            $mailer->send($mail);
//
//            $this->addFlash('success', "l'article a ete partage avec succes avec votre amie.");
//
//            return $this->redirectToRoute('app_accueil');
//
//        }
//
//        return $this->render('post/share.html.twig', compact('form', 'post'));
//    }

}
