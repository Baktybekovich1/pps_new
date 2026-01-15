<?php

namespace App\Form;

use App\Entity\TeacherOrganization;
use App\Entity\Organization;
use App\Entity\Institute;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeacherWorkplaceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('organization', EntityType::class, [
                'class' => Organization::class,
                'choice_label' => 'name',
                'placeholder' => 'Организация',
            ])
            ->add('institute', EntityType::class, [
                'class' => Institute::class,
                'choice_label' => 'name',
                'placeholder' => 'Институт',
                'query_builder' => fn($repo) => $repo->createQueryBuilder('i')->orderBy('i.name', 'ASC'),
            ])->add('active', CheckboxType::class, [
                'label' => 'Активен',
                'required' => false,
            ])
            ->add('regular', CheckboxType::class, [
                'label' => 'Постоянный',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => TeacherOrganization::class]);
    }
}