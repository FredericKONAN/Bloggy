<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters->add('createdAt')
            ->add('publishedAt')
            ->add('author')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('titre', 'Titre'),
            SlugField::new('slug', 'Slug')->setTargetFieldName('titre'),
            TextareaField::new('contenu', 'Description')->hideOnIndex(),
            DateTimeField::new('publishedAt', 'Date de publication'),
            AssociationField::new('author', 'Auteur'),
        ];
    }

}
