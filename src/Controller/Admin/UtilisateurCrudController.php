<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UtilisateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $roles = ['ROLE_USER', 'ROLE_ADMIN'];

        return [
            IdField::new('id', '#')->onlyOnIndex(),
            TextField::new('nom', 'Nom et Prenoms'),
            EmailField::new('email'),
            TextField::new('plainPassword', 'Mot de passe')
                ->setFormType(PasswordType::class)
                ->onlyOnForms()
                ->setRequired($pageName === Crud::PAGE_NEW)
            ,
            ChoiceField::new('roles')
              ->setChoices(array_combine($roles, $roles))
              ->allowMultipleChoices()
              ->renderExpanded()
              ->renderAsBadges()
            ,
        ];
    }

}
