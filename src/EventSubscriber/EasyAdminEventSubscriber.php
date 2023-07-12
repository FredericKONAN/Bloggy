<?php

namespace App\EventSubscriber;

use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class EasyAdminEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher){}

    public function onBeforeEntityPersistedEvent(BeforeEntityPersistedEvent  $event): void
    {
        $utilisateur = $event->getEntityInstance();

        if (($utilisateur instanceof Utilisateur && $utilisateur->plainPassword )) {

            $plainPassword = $utilisateur->plainPassword;
            $mdpHash = $this->passwordHasher->hashPassword($utilisateur, $plainPassword);
            $utilisateur->setPassword($mdpHash);
        }

    }

    public function onBeforeEntityUpdatedEvent(BeforeEntityUpdatedEvent $event): void
    {
        $utilisateur = $event->getEntityInstance();

        if (($utilisateur instanceof Utilisateur && $utilisateur->plainPassword )) {

            $plainPassword = $utilisateur->plainPassword;
            $mdpHash = $this->passwordHasher->hashPassword($utilisateur, $plainPassword);
            $utilisateur->setPassword($mdpHash);
        }

    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => 'onBeforeEntityPersistedEvent',
            BeforeEntityUpdatedEvent::class => 'onBeforeEntityUpdatedEvent',
        ];
    }
}
