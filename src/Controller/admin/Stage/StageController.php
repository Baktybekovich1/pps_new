<?php

namespace App\Controller\admin\Stage;

use App\Entity\Directors;
use App\Entity\InnovativeEducationList;
use App\Entity\InnovativeEducationSubtitle;
use App\Entity\PersonalAwards;
use App\Entity\PersonalAwardsSubtitle;
use App\Entity\ResearchActivitiesList;
use App\Entity\ResearchActivitiesSubtitle;
use App\Entity\SocialActivitiesList;
use App\Entity\SocialActivitiesSubtitle;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StageController extends AbstractDashboardController
{


    #[Route('/admin/stage', name: 'admin_stage')]
    public function index(): Response
    {
        return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

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
            ->setTitle('Админ редактирование этапов');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::subMenu('Personal Awards', 'fa fa-1')->setSubItems([
            MenuItem::linkToCrud('Personal awards Title', 'fa fa-1', PersonalAwards::class),
            MenuItem::linkToCrud('Personal awards Subtitle', 'fa fa-bars-staggered', PersonalAwardsSubtitle::class)
        ]);
        yield MenuItem::subMenu('Innovative Education', 'fa fa-2')->setSubItems([
            MenuItem::linkToCrud('Innovative Education Title', 'fa fa-2', InnovativeEducationList::class),
            MenuItem::linkToCrud('Innovative Education Subtitle', 'fa fa-bars-staggered', InnovativeEducationSubtitle::class)
        ]);
        yield MenuItem::subMenu('Research Activities', 'fa fa-3')->setSubItems([
            MenuItem::linkToCrud('Research Activities Title', 'fa fa-3', ResearchActivitiesList::class),
            MenuItem::linkToCrud('Research Activities Subtitle', 'fa fa-bars-staggered', ResearchActivitiesSubtitle::class)
        ]);
        yield MenuItem::subMenu('Social Activities', 'fa fa-4')->setSubItems([
            MenuItem::linkToCrud('Social Activities Title', 'fa fa-4', SocialActivitiesList::class),
            MenuItem::linkToCrud('Social Activities Subtitle', 'fa fa-bars-staggered', SocialActivitiesSubtitle::class)
        ]);


    }
}
