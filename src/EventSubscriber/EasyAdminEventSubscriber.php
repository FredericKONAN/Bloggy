<?php

namespace App\EventSubscriber;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private EntityManagerInterface $entityManager,private UserPasswordHasherInterface $passwordHasher){}


//    public function onKernelRequest(RequestEvent $event)
//    {
////       Ceci permet de recuperer la route courante dd($event->getRequest()->attributes->get('_route'));
//        if('admin' === $event->getRequest()->attributes->get('_route')){
//            $this->entityManager->getFilters()->disable('published_filter');
//        }
//    }

    public function updatePassword(BeforeEntityPersistedEvent|BeforeEntityUpdatedEvent  $event): void
    {

        $this->hashpassword($event);
//        $this->hashpasswordEvent($event->getEntityInstance());
//        $utilisateur = $event->getEntityInstance();
//
//        if (($utilisateur instanceof Utilisateur && $utilisateur->plainPassword )) {
//
//            $plainPassword = $utilisateur->plainPassword;
//            $mdpHash = $this->passwordHasher->hashPassword($utilisateur, $plainPassword);
//            $utilisateur->setPassword($mdpHash);
//        }

    }


    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'updatePassword',
            BeforeEntityUpdatedEvent::class => 'updatePassword',
//            KernelEvents::REQUEST => 'onKernelRequest'
        ];
    }

    private function hashPassword($event):void
    {

        $utilisateur = $event->getEntityInstance();
        if (($utilisateur instanceof Utilisateur && $utilisateur->plainPassword )) {

            $plainPassword = $utilisateur->plainPassword;
            $mdpHash = $this->passwordHasher->hashPassword($utilisateur, $plainPassword);
            $utilisateur->setPassword($mdpHash);
        }
    }
}
