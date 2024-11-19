<?php

namespace App\Controller\admin\Stage;

use App\Entity\ResearchActivitiesList;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ResearchActivitiesListCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ResearchActivitiesList::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
        ];
    }

}
