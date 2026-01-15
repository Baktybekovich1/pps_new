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

class TeacherWorkplaceDirectorFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('active', CheckboxType::class, [
                'label' => 'Активен',
                'required' => false,
            ])
            ->add('regular', CheckboxType::class, [
                'label' => 'Штатный',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => TeacherOrganization::class]);
    }
}