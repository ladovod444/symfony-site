<?php

namespace App\DataFixtures;


use App\Entity\Page;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PageFixtures extends Fixture
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
    for ($i = 1; $i < 60; $i++) {

      $page = new Page($user);
      $page->setTitle('Title ' . $i);
      $page->setBody('Body ' . $i);
      $page->setAuthor('Author' . $i);
      $page->setStatus(true);

      $manager->persist($page);
    }
    $manager->flush();
  }
}
