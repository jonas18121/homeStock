<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\StorageSpace;
use App\Entity\User;
use App\Repository\BookingRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\StorageSpaceRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private UserRepository $userRepository;
    private StorageSpaceRepository $storageSpaceRepository;
    private CategoryRepository $categoryRepository;
    private BookingRepository $bookingRepository;
    private CommentRepository $commentRepository;

    public function __construct(
        UserRepository $userRepository,
        StorageSpaceRepository $storageSpaceRepository,
        CategoryRepository $categoryRepository,
        BookingRepository $bookingRepository,
        CommentRepository $commentRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->storageSpaceRepository = $storageSpaceRepository;
        $this->categoryRepository = $categoryRepository;
        $this->bookingRepository = $bookingRepository;
        $this->commentRepository = $commentRepository;
    } 

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render("bundles/EasyAdminBundle/welcome.html.twig",
            [
                "nb_user" => $this->userRepository->countUser(),
                "nb_storageSpace" => $this->storageSpaceRepository->countStorageSpace(),
                "nb_category" => $this->categoryRepository->countCategory(),
                "nb_booking" => $this->bookingRepository->countBooking(),
                "nb_comment" => $this->commentRepository->countComment()

            ]
        );
        // return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('HomeStock');
    }

    public function configureMenuItems(): iterable
    {
        /** @var string */
        $domain = $this->getParameter('app.domain');
        yield MenuItem::linkToUrl('Revenir à l\'accueil','fas fa-arrow-circle-left', $domain);

        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Utilisateur', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Espace de stokage', 'fas fa-warehouse', StorageSpace::class);
        yield MenuItem::linkToCrud('Catégorie', 'fas fa-layer-group', Category::class);
        yield MenuItem::linkToCrud('Réservation', 'fas fa-book-open', Booking::class);
        yield MenuItem::linkToCrud('Commentaire', 'fas fa-comment', Comment::class);
    }
}
