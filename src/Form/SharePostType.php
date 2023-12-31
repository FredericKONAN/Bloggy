<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class SharePostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_expediteur', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min'=> 3])
                ],
            ])
            ->add('mail_expediteur', TextType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(),
                    new  Email(['mode' => Email::VALIDATION_MODE_HTML5])
                ],
            ])
            ->add('mail_destinateur', TextType::class, [
                'label' => 'Mail de votre ami(e)',
                'constraints' => [
                    new NotBlank(),
                    new  Email(['mode' => Email::VALIDATION_MODE_HTML5]),
                ],
            ])
            ->add('commentaires', TextareaType::class, [
                'label'=> ' Commentaire',
                'help' => 'Laissez-le vide si vous le souhaitez (facultatif).',
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
