<?php

namespace App\EventSubscriber;

use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class EasyAdminEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher){}

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
