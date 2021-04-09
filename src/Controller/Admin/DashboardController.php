<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\StorageSpace;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('HomeStock');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Revenir à l\'accueil','fas fa-arrow-circle-left', 'http://localhost:8000');

        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Espace de stokage', 'fas fa-warehouse', StorageSpace::class);
        yield MenuItem::linkToCrud('Catégorie', 'fas fa-list', Category::class);
    }
}
