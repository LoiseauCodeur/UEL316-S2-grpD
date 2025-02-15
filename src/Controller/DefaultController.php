<?php
namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\ArticleRepository;
use App\Entity\Post;
use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $postRepository, ArticleRepository $articleRepository): Response
    {
        $posts = $postRepository->findBy([], ['createdAt' => 'DESC']);
        $articles = $articleRepository->findBy([], ['publishDay' => 'DESC']);

        return $this->render('Default/index.html.twig', [
            'posts' => $posts,
            'articles' => $articles,
        ]);
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function showArticle(Article $article): Response
    {
        return $this->render('Default/article_show.html.twig', [
            'article' => $article
        ]);
    }

    #[Route('/post/{id}', name: 'post_show')]
    public function showPost(Post $post): Response
    {
        return $this->render('Default/post_show.html.twig', [
            'post' => $post
        ]);
    }
}