<?php

namespace App\Controller\admin\Stage;

use App\Entity\PersonalAwards;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PersonalAwardsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PersonalAwards::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
        ];
    }

}
