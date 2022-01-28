<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImporterFilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   // trouvé dans la doc symfony
        $builder
            ->add('fichier_csv', FileType::class, [
                'label' => 'XSLS file',

                'mapped' => false,
                'required' => false,



            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }
}