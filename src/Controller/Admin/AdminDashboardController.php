<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\Article;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class AdminDashboardController extends AbstractDashboardController
{
    public function __construct(
        private PostRepository $postRepository,
        private ArticleRepository $articleRepository,
        private UserRepository $userRepository,
        private CategoryRepository $categoryRepository
    ) {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $stats = [
            'countArticles' => $this->articleRepository->count([]),
            'countPosts' => $this->postRepository->count([]),
            'countUsers' => $this->userRepository->count([]),
            'countCategories' => $this->categoryRepository->count([]),
        ];

        $stats['recentArticles'] = $this->articleRepository
            ->findBy([], ['publishDay' => 'DESC'], 5);

        $stats['recentPosts'] = $this->postRepository
            ->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->render('admin/dashboard.html.twig', $stats);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Articles', 'fas fa-newspaper', Article::class);
        yield MenuItem::linkToCrud('Posts', 'fas fa-file-text', Post::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
    }
}