<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'email',
                EmailType::class,
                [
                    'attr' => ['placeholder' => "Entrez votre email."],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Email(['message' => "Email incorrecte !"]),
                    ]
                ]
            )
            ->add(
                'username',
                TextType::class,
                [
                    'attr' => ['placeholder' => "Entrez votre pseudo."],
                    'constraints' => [new Assert\NotBlank()]
                ]
            )
            ->add(
                'password',
                PasswordType::class,
                [
                    'attr' => ['placeholder' => "Entrez votre mot de passe."],
                    'constraints' => [
                        new Assert\Length(["min" => 6, "minMessage" => "Le mot de passe est trop court (Min. 6 caractères)"])
                    ]
                ]
            )
            ->add('submit', SubmitType::class, [
                'label' => "Inscrivez-vous",
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
