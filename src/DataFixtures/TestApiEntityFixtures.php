<?php

namespace App\DataFixtures;


use App\Entity\Page;
use App\Entity\TestApiEntity;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestApiEntityFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    private UserRepository $userRepository;

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function __construct(UserPasswordHasherInterface $hasher, UserRepository $userRepository)
    {
        $this->hasher = $hasher;
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager): void
    {
        $user = $this->userRepository->findOneBy(['email' => 'ladovod@gmail.com']);
        for ($i = 1; $i < 10; $i++) {

            $entity = new TestApiEntity();
            $entity->setTitle('Title ' . $i);
            $entity->setDescription('Body ' . $i);

            $manager->persist($entity);
        }
        $manager->flush();
    }
}
