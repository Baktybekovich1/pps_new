<?php

namespace App\Controller\admin;

use App\Entity\QuestionTitle;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class QuestionTitleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return QuestionTitle::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
        ];
    }
}
