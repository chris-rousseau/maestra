<?php

namespace App\Form;

use App\Entity\Pill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de la pilule'
            ])
            ->add('description', null, [
                'label' => 'Description de la pilule'
            ])
            ->add('pillImg', FileType::class, [
                'label' => 'Ajouter une photo',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Merci de téleverser une image au bon format.',
                    ])
                ],
            ])
            ->add('reimbursed', ChoiceType::class, [
                'label' => 'Remboursement',
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'choices'  => [
                    '0%' => 0,
                    '65%' => 65,
                ],
            ])
            ->add('generic', null, [
                'label' => 'Générique'
            ])
            ->add('posology', null, [
                'label' => 'Posologie de la pilule'
            ])
            ->add('type')
            ->add('generation', ChoiceType::class, [
                'label' => 'Génération',
                'required' => true,
                'multiple' => false,
                'choices'  => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                ],
            ])
            ->add('interruption', null, [
                'label' => 'Prise en continu'
            ])
            ->add('laboratory', null, [
                'label' => 'Laboratoire'
            ])
            ->add('delay_intake', null, [
                'label' => 'Délai d\'oubli autorisé'
            ])
            ->add('composition');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pill::class,
        ]);
    }
}
