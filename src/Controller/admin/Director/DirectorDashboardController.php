<?php

namespace App\Controller\admin\Director;

use App\Controller\admin\InstituteCrudController;
use App\Entity\Institute;
use App\Entity\Teacher;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DirectorDashboardController extends AbstractDashboardController
{
    #[Route('/director', name: 'director')]
    public function index(): Response
    {


        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
         $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
         return $this->redirect($adminUrlGenerator->setController(InstituteDirectorCrudController::class)->generateUrl());

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
            ->setTitle('Панель управления Директора Института');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud(' Параметры Института', 'fas fa-list', Institute::class)->setController(InstituteDirectorCrudController::class);
        yield MenuItem::linkToCrud(' Параметры Учителей', 'fas fa-list', Teacher::class)->setController(TeacherDirectorCrudController::class);
    }
}
