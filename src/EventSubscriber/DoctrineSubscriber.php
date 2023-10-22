<?php

namespace App\EventSubscriber;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class DoctrineSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager){}

    public function onKernelRequest(RequestEvent $event): void
    {
        //Ceci permet de recuperer la route courante dd($event->getRequest()->attributes->get('_route'));
        if('admin' !== $event->getRequest()->attributes->get('_route')){
//            $this->entityManager->getFilters()->disable('published_filter');
            $filter = $this->entityManager->getFilters()->enable('published_filter');
            $filter->setParameter('current_datetime', new \DateTimeImmutable());
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
