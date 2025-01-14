<?php

namespace App\Controller\admin;

use App\Entity\Institutions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InstitutionsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Institutions::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('university'),
            TextField::new('reduction'),
            IntegerField::new('totalTeachers'),
        ];
    }

}
