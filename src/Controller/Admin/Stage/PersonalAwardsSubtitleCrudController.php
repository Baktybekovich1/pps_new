<?php

namespace App\Controller\s\Stage;

use App\Entity\PersonalAwardsSubtitle;
use App\Repository\PersonalAwardsRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PersonalAwardsSubtitleCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return PersonalAwardsSubtitle::class;
    }


    public function configureFields(string $pageName): iterable
    {

        return [

            TextField::new('name'),
            NumberField::new('points', 'Баллы за награду'),
            AssociationField::new('title', 'Title')->renderAsNativeWidget(),
        ];


    }
}
