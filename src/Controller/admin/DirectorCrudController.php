<?php

//namespace App\Controller\admin;
//
//use App\Entity\Director;
//use App\Entity\User;
//use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
//use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
//use Symfony\Component\Form\Extension\Core\Type\PasswordType;
//use Symfony\Component\Security\Http\Attribute\IsGranted;
//use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
//use Doctrine\ORM\EntityManagerInterface;

//class DirectorCrudController extends AbstractCrudController
//{
//
//    public function __construct(
//        private UserPasswordHasherInterface $hasher
//    ) {}
//
//    public static function getEntityFqcn(): string
//    {
//        return Director::class;
//    }
//
//
//    public function configureFields(string $pageName): iterable
//    {
//        yield IdField::new('id')->onlyOnIndex();
//
//        // встроенная форма User
//        yield FormField::addPanel('Данные пользователя')->onlyWhenCreating();
//        yield TextField::new('user.username', 'Username');
//        yield TextField::new('user.password', 'Пароль')
//            ->onlyWhenCreating()
//            ->setFormType(PasswordType::class)
//            ->setRequired(true);
//
//        yield AssociationField::new('institute', 'Институт');
//    }
//
//    /**
//     * Создаём User и Director в одном действии
//     */
//    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
//    {
//        /** @var Director $director */
//        $director = $entityInstance;
//
//        if (!$director->getUser()) {
//            $user = new User();
//            $user->setUsername($director->getUser()->getUsername());   // ← уже заполнено формой
//            $plainPassword = $this->getContext()->getRequest()->request->get('Director')['user']['password'] ?? '';
//            $user->setPassword($this->hasher->hashPassword($user, $plainPassword));
//            $user->setRoles(['ROLE_DIRECTOR']);
//            $entityManager->persist($user);
//            $director->setUser($user);
//        }
//
//        parent::persistEntity($entityManager, $director);
//    }
//
//}

namespace App\Controller\admin;

use App\Entity\Director;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DirectorCrudController extends AbstractCrudController implements EventSubscriberInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BeforeEntityPersistedEvent::class => ['hashPasswordAndRole'],
            BeforeEntityUpdatedEvent::class => ['hashPasswordAndRole']
        ];
    }

    public static function getEntityFqcn(): string
    {
        return Director::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->onlyOnIndex();

        yield FormField::addPanel('Данные пользователя');
        yield TextField::new('user.username', 'Username');
        yield TextField::new('user.password', 'Пароль')
            ->setFormType(PasswordType::class)
            ->setRequired(true);
        yield TextField::new('user.password', 'Новый пароль')
            ->setFormType(PasswordType::class)
            ->setRequired(false)                       // ← не обязательно
            ->onlyWhenUpdating();

        yield AssociationField::new('institute', 'Институт');
    }

    public function hashPasswordAndRole($event)
    {
        $entity = $event->getEntityInstance();
        if (!$entity instanceof Director) return;

        $user = $entity->getUser();
        if (!$user) return;

        $plainPassword = $this->getContext()->getRequest()->request->all()['Director']['user_password'];
        if ($plainPassword && is_string($plainPassword)) {
            $user->setPassword($this->hasher->hashPassword($user, $plainPassword));
        }

        $user->setRoles(['ROLE_DIRECTOR']);
    }


}
