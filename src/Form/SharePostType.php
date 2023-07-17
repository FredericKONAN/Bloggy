<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SharePostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_expediteur', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('mail_expediteur', EmailType::class, [
                'label' => 'Email'
            ])
            ->add('mail_destinateur', EmailType::class, [
                'label' => 'Mail de votre ami(e)'
            ])
            ->add('commentaires', TextareaType::class, [
                'label'=> ' Commentaire'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
