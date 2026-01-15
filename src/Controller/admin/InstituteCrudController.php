<?php

namespace App\Controller\admin;

use App\Entity\Institute;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InstituteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Institute::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            AssociationField::new('organization','Организация')->renderAsNativeWidget(),
            TextField::new('reduction'),
            IntegerField::new('teacherTotal'),

        ];
    }

}
