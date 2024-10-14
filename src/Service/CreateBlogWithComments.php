<?php

namespace App\Service;

use App\Entity\Blog;
use App\Entity\BlogComment;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(public: true)]
class CreateBlogWithComments
{

  public function __construct(private readonly UserRepository $userRepository,
                              private readonly EntityManagerInterface $entityManager)
  {

  }

  public function createBlogWithComments($user): void {
   // $user = $this->userRepository->findOneBy(['email' => 'ladovod@gmail.com']);
    $blog = new Blog($user);

    $blog->setTitle('title')
      ->setDescription('description')
      ->setText('text')
      ->setStatus('pending');

    $blog->addComment((new BlogComment())->setText('blog comment text'));

    $this->entityManager->persist($blog);
    $this->entityManager->flush();

  }

}