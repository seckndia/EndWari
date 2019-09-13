<?php

namespace App\Form;

use App\Entity\Envoie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnvoiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomEnvoyeur')
            ->add('prenomEnvoyeur')
            ->add('telEnvoyeur')
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Envoie::class,
        ]);
    }
}
