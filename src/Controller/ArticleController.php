<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'article_index')]
    public function index(
        ArticleRepository $articleRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $query = $articleRepository->createQueryBuilder('a')
            ->leftJoin('a.Author', 'u')
            ->leftJoin('a.Category', 'c')
            ->select('a, u, c');

        $search = $request->query->get('search');
        if ($search) {
            $query->andWhere('a.Title LIKE :search OR a.content LIKE :search')
                  ->setParameter('search', '%'.$search.'%');
        }

        $dateFilter = $request->query->get('date');
        if ($dateFilter) {
            $now = new \DateTime();
            switch ($dateFilter) {
                case 'today':
                    $start = new \DateTime('today');
                    $query->andWhere('a.publishDay BETWEEN :start AND :end')
                          ->setParameter('start', $start)
                          ->setParameter('end', $now);
                    break;
                case 'week':
                    $weekAgo = new \DateTime('-1 week');
                    $query->andWhere('a.publishDay >= :weekAgo')
                          ->setParameter('weekAgo', $weekAgo);
                    break;
                case 'month':
                    $monthAgo = new \DateTime('-1 month');
                    $query->andWhere('a.publishDay >= :monthAgo')
                          ->setParameter('monthAgo', $monthAgo);
                    break;
            }
        }

        $categoryId = $request->query->get('category');
        if ($categoryId) {
            $query->andWhere('c.id = :categoryId')
                  ->setParameter('categoryId', $categoryId);
        }

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            6,
            [
                'defaultSortFieldName' => 'a.publishDay',
                'defaultSortDirection' => 'DESC',
                'sortFieldWhitelist' => ['a.publishDay', 'a.Title']
            ]
        );

        $categories = $articleRepository->createQueryBuilder('a')
            ->select('DISTINCT c.id, c.Name')
            ->leftJoin('a.Category', 'c')
            ->where('c.id IS NOT NULL')
            ->orderBy('c.Name', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('article/index.html.twig', [
            'articles' => $pagination,
            'search' => $search,
            'dateFilter' => $dateFilter,
            'categoryId' => $categoryId,
            'categories' => $categories
        ]);
    }

    #[Route('/article/{id}', name: 'article_show')]
    public function show($id, ArticleRepository $articleRepository): Response
    {
        $article = $articleRepository->find($id);

        if (!$article) {
            throw $this->createNotFoundException('Article non trouvé');
        }

        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }
}