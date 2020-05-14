<?php

namespace App\Form;

use App\Entity\Intervention;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment')
            ->add('operating_system')
            ->add('equipment')
            ->add('technicians')
            ->add('tasks')
            ->add('client')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class,
        ]);
    }
}
