<?php

namespace App\Repository;

use App\Entity\Blog;
use App\Filter\BlogFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blog>
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    /**
     * @return Blog[] Returns an array of Blog objects
     */
    public function findByBlogFilter(BlogFilter $blogFilter) {
      $blogs = $this->createQueryBuilder('b');
      if ($blogFilter->getTitle() || $blogFilter->getDescription()) {

        if ($blogFilter->getTitle()) {
          $blogs->where('b.title LIKE :title')
            ->setParameter('title', '%' . $blogFilter->getTitle() . '%');
        }
        if ($blogFilter->getTitle() && $blogFilter->getDescription()) {
          $blogs->orWhere('b.description LIKE :description')
            ->setParameter('description', '%' . $blogFilter->getDescription() . '%');
        }
        if (!$blogFilter->getTitle() && $blogFilter->getDescription()) {
          $blogs->where('b.description LIKE :description')
            ->setParameter('description', '%' . $blogFilter->getDescription() . '%');
        }




      }

      //dd($blogs->getQuery()->getSQL());

      return $blogs->getQuery()->getResult();
    }

    //    /**
    //     * @return Blog[] Returns an array of Blog objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Blog
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
