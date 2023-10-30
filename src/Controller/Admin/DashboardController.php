<?php

namespace App\Controller\Admin;

use App\Entity\Tasks;
use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        $url = $adminUrlGenerator->setController(UsersCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Taskmanager');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Home');
        yield MenuItem::linkToRoute('Home', 'fa fa-home', 'homepage');
        yield MenuItem::section('Tasks');
        yield MenuItem::linkToCrud('All Task', 'fa-sharp fa-solid fa-thumbtack', Tasks::class);
        yield MenuItem::section('Users');
        yield MenuItem::linkToCrud('All User', 'fa-solid fa-user', Users::class);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
