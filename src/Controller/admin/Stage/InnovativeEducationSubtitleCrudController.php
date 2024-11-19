<?php

namespace App\Controller\admin\Stage;

use App\Entity\InnovativeEducationSubtitle;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InnovativeEducationSubtitleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InnovativeEducationSubtitle::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            NumberField::new('points', 'Баллы за награду'),
            AssociationField::new('title', 'Title')->renderAsNativeWidget()
        ];
    }

}
