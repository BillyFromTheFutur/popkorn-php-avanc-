<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
/*
██████╗░██████╗░░█████╗░██████╗░██╗░░░░░███████╗███╗░░░███╗███████╗
██╔══██╗██╔══██╗██╔══██╗██╔══██╗██║░░░░░██╔════╝████╗░████║██╔════╝
██████╔╝██████╔╝██║░░██║██████╦╝██║░░░░░█████╗░░██╔████╔██║█████╗░░
██╔═══╝░██╔══██╗██║░░██║██╔══██╗██║░░░░░██╔══╝░░██║╚██╔╝██║██╔══╝░░
██║░░░░░██║░░██║╚█████╔╝██████╦╝███████╗███████╗██║░╚═╝░██║███████╗
╚═╝░░░░░╚═╝░░╚═╝░╚════╝░╚═════╝░╚══════╝╚══════╝╚═╝░░░░░╚═╝╚══════╝

=> je n'ai pas réussis a bloquer le a bloquer le score entre 1 et 10
*/

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('score')
            ->add('email')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}