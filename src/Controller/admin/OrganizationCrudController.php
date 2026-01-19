<?php

namespace App\Controller\admin;

use App\Entity\Organization;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class OrganizationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Organization::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('name'),
            FormField::addPanel('Фото'),
            TextField::new('photoFile', 'Фото')   // ← обычное поле, но с VichImageType
            ->setFormType(VichImageType::class)
                ->onlyOnForms(),   // только в форме

            // -------- preview --------
            ImageField::new('photoFilename', 'Фото')
                ->setBasePath('/uploads/organizations')
                ->onlyOnIndex()   // только в списке
        ];
    }

}
