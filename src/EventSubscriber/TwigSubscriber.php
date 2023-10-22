<?php

namespace App\EventSubscriber;

use App\Repository\PostRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;

class TwigSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly PostRepository $postRepo,
        private readonly Environment $twig,
        private readonly CacheInterface $cache
    ){}

    public function onKernelController(ControllerEvent $event): void
    {
       $totalPost = $this->cache->get('app.latestPots', function (ItemInterface $item){
           $item->expiresAfter(30);

           return $this->postRepo->count([]);

       });


        $mostCommentPost = $this->cache->get('app.mostComment', function (ItemInterface $item){
            $item->expiresAfter(20);

            return $this->postRepo->findMostCommented(5);

        });


        $latestPost = $this->cache->get('app.latestPost', function (ItemInterface $item){
            $item->expiresAfter(40);

            return $this->postRepo->findBy([], ['publishedAt' => 'DESC'], 5);

        });

            $this->twig->addGlobal('totalPost',$totalPost);
            $this->twig->addGlobal('latestPost',$latestPost);
            $this->twig->addGlobal('mostCommentPost',$mostCommentPost);
    }

    public static function getSubscribedEvents(): array
    {
        return [
//            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
