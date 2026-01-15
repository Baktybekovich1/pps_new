<?php

namespace App\Controller\admin;

use App\Entity\TeacherAnswer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TeacherAnswerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TeacherAnswer::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('link'),
            BooleanField::new('active'),
            AssociationField::new('subtitle'),
            AssociationField::new('teacher'),
        ];
    }

}
