<?php

namespace App\Controller\admin;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Entity\Director;
use App\Entity\Institute;
use App\Entity\InstitutionQuestion;
use App\Entity\InstitutionQuestionOption;
use App\Entity\Organization;
use App\Entity\Position;
use App\Entity\QuestionSubtitle;
use App\Entity\QuestionTitle;
use App\Entity\Stage;
use App\Entity\Teacher;
use App\Entity\TeacherAnswer;
use App\Entity\TeacherOrganization;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractDashboardController
{

    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Option 1. You can make your dashboard redirect to some common page of your backend
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle(' Администрирование');
    }

    public function configureMenuItems(): iterable
    {
//        yield MenuItem::linkToCrud('Director label', 'fas fa-list', Director::class);
//        yield MenuItem::linkToCrud('Institution Questions label', 'fas fa-list', InstitutionQuestion::class);
//        yield MenuItem::linkToCrud('Institution Question Options label', 'fas fa-list', InstitutionQuestionOption::class);
        yield MenuItem::linkToCrud('Пользователи', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Организации', 'fas fa-school', Organization::class);
        yield MenuItem::linkToCrud('Институты', 'fas fa-briefcase', Institute::class);
        yield MenuItem::linkToCrud('Позиции учителей', 'fas fa-chalkboard-teacher', Position::class);
        yield MenuItem::linkToCrud('Директора Интститутов', 'fas fa-user-tie', Director::class);
        yield MenuItem::linkToCrud('Учителя', 'fas fa-user-graduate', Teacher::class);
        yield MenuItem::subMenu('Вопросы учителей', 'fas fa-sitemap')
            ->setSubItems([
                MenuItem::linkToCrud('Этапы', 'fas fa-list-ol', Stage::class),
                MenuItem::linkToCrud('Вопросы', 'fas fa-poll', QuestionTitle::class),
                MenuItem::linkToCrud('Подвопросы', 'fas fa-vote-yea', QuestionSubtitle::class),
                MenuItem::linkToCrud('Ответы учителей', 'fas fa-vote-yea', TeacherAnswer::class),
                ]);
    }
}
