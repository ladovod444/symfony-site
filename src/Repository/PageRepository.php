<?php

namespace App\Repository;

use App\Entity\Page;
use App\Filter\PageFilter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Page>
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

  public function findByPageFilter(PageFilter $pageFilter) {
    $pages = $this->createQueryBuilder('p');
    if ($pageFilter->getTitle() /*|| $blogFilter->getDescription()*/) {
      $pages->where('p.title LIKE :title')
        ->setParameter('title', '%' . $pageFilter->getTitle() . '%');
    }

      if ($pageFilter->getTitle()) {
        $pages->where('p.title LIKE :title')
          ->setParameter('title', '%' . $pageFilter->getTitle() . '%');
      }
      if ($pageFilter->getTitle() && $pageFilter->getBody()) {
        $pages->orWhere('p.body LIKE :body')
          ->setParameter('body', '%' . $pageFilter->getBody() . '%');
      }
      if (!$pageFilter->getTitle() && $pageFilter->getBody()) {
        $pages->where('p.body LIKE :body')
          ->setParameter('body', '%' . $pageFilter->getBody() . '%');
      }


        return $pages->getQuery()->getResult();

    }
}
