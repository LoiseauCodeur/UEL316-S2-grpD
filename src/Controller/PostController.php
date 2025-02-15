<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class PostController extends AbstractController
{
    #[Route('/posts', name: 'post_index')]
    public function index(
        PostRepository $postRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $query = $postRepository->createQueryBuilder('p')
            ->leftJoin('p.author', 'a')
            ->select('p, a'); 

        $search = $request->query->get('search');
        if ($search) {
            $query->andWhere('p.title LIKE :search OR p.content LIKE :search')
                  ->setParameter('search', '%'.$search.'%');
        }

        $dateFilter = $request->query->get('date');
        if ($dateFilter) {
            $now = new \DateTime();
            switch ($dateFilter) {
                case 'today':
                    $start = new \DateTime('today');
                    $query->andWhere('p.createdAt BETWEEN :start AND :end')
                          ->setParameter('start', $start)
                          ->setParameter('end', $now);
                    break;
                case 'week':
                    $weekAgo = new \DateTime('-1 week');
                    $query->andWhere('p.createdAt >= :weekAgo')
                          ->setParameter('weekAgo', $weekAgo);
                    break;
                case 'month':
                    $monthAgo = new \DateTime('-1 month');
                    $query->andWhere('p.createdAt >= :monthAgo')
                          ->setParameter('monthAgo', $monthAgo);
                    break;
            }
        }

        $authorId = $request->query->get('author');
        if ($authorId) {
            $query->andWhere('a.id = :authorId')
                  ->setParameter('authorId', $authorId);
        }

        $pagination = $paginator->paginate(
            $query, 
            $request->query->getInt('page', 1), 
            6, 
            [
                'defaultSortFieldName' => 'p.createdAt',
                'defaultSortDirection' => 'DESC',
                'sortFieldWhitelist' => ['p.createdAt', 'p.title']
            ]
        );

        $authors = $postRepository->createQueryBuilder('p')
            ->select('DISTINCT a.id, a.name, a.firstname')
            ->leftJoin('p.author', 'a')
            ->where('a.id IS NOT NULL')
            ->orderBy('a.name', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('post/index.html.twig', [
            'posts' => $pagination,
            'search' => $search,
            'dateFilter' => $dateFilter,
            'authorId' => $authorId,
            'authors' => $authors
        ]);
    }
}