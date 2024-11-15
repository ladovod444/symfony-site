<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\BlogMeta;
use App\Entity\Page;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Core\DateTime;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class BlogFixtures extends Fixture implements FixtureGroupInterface
{
  private UserPasswordHasherInterface $hasher;
  private UserRepository $userRepository;

  public function __construct(UserPasswordHasherInterface $hasher, UserRepository $userRepository)
  {
    $this->hasher = $hasher;
    $this->userRepository = $userRepository;
  }

    public static function getGroups(): array
    {
        return ['blog'];
    }

  public function load(ObjectManager $manager): void
  {
    $user  = $this->userRepository->findOneBy(['email' => 'ladovod@gmail.com']);
    for ($i = 0; $i < 3; $i++) {

        $description = " Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias aliquam aspernatur, culpa dicta, dolor earum eum facere itaque iure iusto molestias nobis non officiis possimus quibusdam quisquam sequi vel, voluptatem!";

      $blog = new Blog($user);
      $blog->setTitle('Title ' . $i);
      $blog->setDescription($description . ' ' . $i);
      $blog->setText('Text ' . $i);
      $blog->setStatus(true);
      $blog->setBlockedAtValue();

      $blog_meta = new BlogMeta();
      $blog_meta->setDescription($description);
      $blog_meta->setAuthor($user->getEmail());
      $blog_meta->setKeywords($description);

      $blog->setBlogMeta($blog_meta);

      $manager->persist($blog);
    }
    $manager->flush();
  }
}
