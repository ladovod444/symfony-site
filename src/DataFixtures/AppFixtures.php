<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\Page;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
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

    for ($i = 2; $i < 4; $i++) {

      $user = new User();
      $user->setEmail('email' . $i . '@email.com');
      $password = $this->hasher->hashPassword($user, 'pass_123' . $i);
      $user->setPassword($password);

      //$manager->persist($user);
    }
    $manager->flush();
  }
}
