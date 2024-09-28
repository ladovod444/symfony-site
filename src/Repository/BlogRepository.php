<?php

namespace App\Repository;

use App\Entity\Blog;
use App\Entity\User;
use App\Filter\BlogFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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
  public function findByBlogFilter(BlogFilter $blogFilter) :QueryBuilder
  {
    $blogs = $this->createQueryBuilder('b');

    // Элемент оптимизации запроса
    $blogs->leftJoin(User::class, 'u', 'WITH', 'u.id = b.user');
                                                                                     // здесь именно
                                                                                 // связанная сущность user

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

    if ($blogFilter->getUser()) {
      $blogs->andWhere('b.user = :user')
        ->setParameter('user', $blogFilter->getUser());
    }

    $blogs->orderBy('b.id', 'DESC');


    //dd($blogs->getQuery()->getSQL());

    //return $blogs->getQuery()->getResult();
    // для пагинации вовращаем Query Builder
    return $blogs;
  }

  public function getBlogs()
  {
    return $this->createQueryBuilder('b')
      ->setMaxResults(6)
      ->getQuery()
      ->getResult();
  }

}
