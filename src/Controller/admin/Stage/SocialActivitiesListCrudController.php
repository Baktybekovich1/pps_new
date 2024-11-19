<?php

namespace App\Controller\admin\Stage;

use App\Entity\SocialActivitiesList;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SocialActivitiesListCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SocialActivitiesList::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
        ];
    }

}
