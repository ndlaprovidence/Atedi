<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Equipment;
use App\Entity\Intervention;
use App\Entity\OperatingSystem;
use Doctrine\ORM\EntityRepository;
use App\Repository\ClientRepository;
use App\Repository\EquipmentRepository;
use Symfony\Component\Form\AbstractType;
use App\Repository\OperatingSystemRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class InterventionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'query_builder' => function (ClientRepository $cr) {
                    return $cr->createQueryBuilder('c')
                        ->orderBy('c.id', 'DESC');
                }
            ])
            ->add('operating_system', EntityType::class, [
                'class' => OperatingSystem::class,
                'query_builder' => function (OperatingSystemRepository $osr) {
                    return $osr->createQueryBuilder('os')
                        ->orderBy('os.id', 'DESC');
                }
            ])
            ->add('equipment', EntityType::class, [
                'class' => Equipment::class,
                'query_builder' => function (EquipmentRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.id', 'DESC');
                }
            ])
            ->add('equipment_complete', ChoiceType::class, [
                'choices' => [
                    'Oui' => 'Oui',
                    'Non' => 'Non',
                ],
            ])
            ->add('comment')
            ->add('tasks')
            ->add('return_date', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intervention::class,
        ]);
    }
}
