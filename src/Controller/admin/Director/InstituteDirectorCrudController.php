<?php

namespace App\Controller\admin\Director;

use App\Entity\Institute;
use App\Entity\Director;
use App\Repository\DirectorRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_DIRECTOR')]
class InstituteDirectorCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Institute::class;
    }

    public function __construct(private DirectorRepository $directorRepository)
    {
    }

    /**
     * Только институт, к которому привязан текущий директор
     */
    public function createIndexQueryBuilder(
        SearchDto        $searchDto,
        EntityDto        $entityDto,
        FieldCollection  $fields,
        FilterCollection $filters
    ): QueryBuilder
    {
//        /** @var Director $director */
        $director = $this->getUser();
        $director = $this->directorRepository->findOneBy(['user' => $director->getId()]);
        $institute = $director->getInstitute();   // ← один институт

        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.id = :institute')
            ->setParameter('institute', $institute->getId());
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('name', 'Название');
        yield TextField::new('reduction');
        yield IntegerField::new('teacherTotal');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Мой институт');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Мой институт', 'fas fa-university', Institute::class);
    }
}