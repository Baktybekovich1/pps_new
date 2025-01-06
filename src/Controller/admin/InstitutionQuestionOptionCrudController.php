<?php

namespace App\Controller\admin;

use App\Entity\InstitutionQuestionOption;
use Doctrine\DBAL\Types\BooleanType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InstitutionQuestionOptionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InstitutionQuestionOption::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', '№')->onlyOnIndex(),
            TextField::new('title', 'Текст'),
            AssociationField::new('question', 'Institution Question')->renderAsNativeWidget(),
            BooleanField::new('link', 'Ссылка'),
            BooleanField::new('option', 'Варианты'),
            IntegerField::new('points', 'Баллы за награду')
        ];
    }

}
