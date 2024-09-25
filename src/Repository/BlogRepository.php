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
  public function findByBlogFilter(BlogFilter $blogFilter)
  {
    $blogs = $this->createQueryBuilder('b');

    if ($blogFilter->getUser()) {
      $blogs->andWhere('b.user = :user')
        ->setParameter('user', $blogFilter->getUser());
    }

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

  public function getBlogs()
  {
    return $this->createQueryBuilder('b')
      ->setMaxResults(6)
      ->getQuery()
      ->getResult();
  }

}
