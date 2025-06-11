<?php

namespace App\Controller\admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('userInfo', 'Фамилия из UserInfo')->onlyOnIndex();
        yield TextField::new('username');
        yield ArrayField::new('roles');

        $password = TextField::new('password')
            ->setFormType(PasswordType::class)
            ->onlyOnForms();

        if ($pageName === Crud::PAGE_EDIT) {
            $password->setRequired(false);
        }

        yield $password;
    }

    public function persistEntity(\Doctrine\ORM\EntityManagerInterface $entityManager, $entityInstance): void
    {
        if ($entityInstance instanceof User && $entityInstance->getPassword()) {
            $entityInstance->setPassword(
                $this->passwordHasher->hashPassword($entityInstance, $entityInstance->getPassword())
            );
        }

        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(\Doctrine\ORM\EntityManagerInterface $entityManager, $entityInstance): void
    {
// хэшировать только если введён новый пароль
        if ($entityInstance instanceof User) {
            $originalUser = $entityManager->getUnitOfWork()->getOriginalEntityData($entityInstance);
            $newPassword = $entityInstance->getPassword();

// если пароль изменился (введён вручную)
            if ($newPassword && $newPassword !== $originalUser['password']) {
                $entityInstance->setPassword(
                    $this->passwordHasher->hashPassword($entityInstance, $newPassword)
                );
            } else {
                $entityInstance->setPassword($originalUser['password']);
            }
        }

        parent::updateEntity($entityManager, $entityInstance);
    }
}
