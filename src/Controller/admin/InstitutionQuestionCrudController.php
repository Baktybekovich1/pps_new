<?php

namespace App\Controller\admin;

use App\Entity\InstitutionQuestion;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InstitutionQuestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InstitutionQuestion::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('title')
        ];
    }
}
