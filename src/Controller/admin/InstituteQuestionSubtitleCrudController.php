<?php

namespace App\Controller\admin;

use App\Entity\InstituteQuestionSubtitle;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InstituteQuestionSubtitleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InstituteQuestionSubtitle::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('name'),
            AssociationField::new('title','Title')->renderAsNativeWidget(),
            IntegerField::new('point'),
        ];
    }

}
