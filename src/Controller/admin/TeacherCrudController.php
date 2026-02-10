<?php

namespace App\Controller\admin;

use App\Entity\Teacher;
use App\Form\TeacherWorkplaceDirectorFormType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TeacherCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Teacher::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),

            TextField::new('email'),
            ArrayField::new('roles')->onlyOnIndex(),

            TextField::new('firstname'),
            TextField::new('lastname'),
            TextField::new('middlename'),

            AssociationField::new('position'),

            CollectionField::new('teacherOrganizations', 'Места работы')
                ->setEntryType(TeacherWorkplaceDirectorFormType::class)
                ->setFormTypeOption('by_reference', false)
                ->allowAdd()
                ->allowDelete()
                ->renderExpanded(), // чтобы было удобно прямо на странице
        ];
    }
}
