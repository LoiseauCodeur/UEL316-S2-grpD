<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function getAllAuthors()
    {
        return $this->createQueryBuilder('p')
            ->select('DISTINCT u.id, u.name, u.firstname')
            ->leftJoin('p.author', 'u')
            ->where('u.id IS NOT NULL')
            ->getQuery()
            ->getResult();
    }
}
