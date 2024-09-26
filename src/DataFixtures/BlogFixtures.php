<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\Page;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BlogFixtures extends Fixture
{
  private UserPasswordHasherInterface $hasher;
  private UserRepository $userRepository;

  public function __construct(UserPasswordHasherInterface $hasher, UserRepository $userRepository)
  {
    $this->hasher = $hasher;
    $this->userRepository = $userRepository;
  }

  public function load(ObjectManager $manager): void
  {
    $user  = $this->userRepository->findOneBy(['email' => 'ladovod@gmail.com']);
    for ($i = 2; $i < 1500; $i++) {

      $blog = new Blog($user);
      $blog->setTitle('Title ' . $i);
      $blog->setDescription('Description ' . $i);
      $blog->setText('Text ' . $i);
      $blog->setStatus(true);

      $manager->persist($blog);
    }
    $manager->flush();
  }
}
