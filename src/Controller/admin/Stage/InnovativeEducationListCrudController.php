<?php

namespace App\Controller\admin\Stage;

use App\Entity\InnovativeEducationList;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
class InnovativeEducationListCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return InnovativeEducationList::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
        ];
    }

}
