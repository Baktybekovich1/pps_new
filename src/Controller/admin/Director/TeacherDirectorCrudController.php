<?php


namespace App\Controller\admin\Director;

use App\Entity\Teacher;
use App\Entity\Director;
use App\Form\TeacherWorkplaceDirectorFormType;
use App\Repository\DirectorRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_DIRECTOR')]
class TeacherDirectorCrudController extends AbstractCrudController
{
    public function __construct(private readonly DirectorRepository $directorRepository)
    {
    }

    public static function getEntityFqcn(): string
    {
        return Teacher::class;

    }

    /**
     * Только учителя текущего директора (по институту)
     */
    public function createIndexQueryBuilder(
        SearchDto        $searchDto,
        EntityDto        $entityDto,
        FieldCollection  $fields,
        FilterCollection $filters
    ): QueryBuilder
    {
        /** @var Director $director */
        $director = $this->getUser();
        $director = $this->directorRepository->findOneBy(['user' => $director->getId()]);
        $institute = $director->getInstitute();   // ← один институт // один институт

        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->join('entity.teacherOrganizations', 'to')
            ->andWhere('to.institute = :institute')
            ->setParameter('institute', $institute);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();
        yield TextField::new('firstName', 'Имя')->onlyOnIndex();
        yield TextField::new('lastName', 'Фамилия')->onlyOnIndex();
        yield TextField::new('email', 'E-mail')->onlyOnIndex();
//        yield BooleanField::new('')

        // встроенная коллекция (только при создании)
        yield CollectionField::new('teacherOrganizations', 'Места работы')
            ->setEntryType(TeacherWorkplaceDirectorFormType::class)
            ->setFormTypeOption('by_reference', false);

        yield FormField::addPanel('Места работы')->onlyWhenUpdating();
    }


    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Мои преподаватели', 'fas fa-chalkboard-teacher', Teacher::class);
    }
}