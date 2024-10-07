<?php

namespace Tests\Kernel\App\Repository;

use App\Factory\BlogFactory;
use App\Factory\UserFactory;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class BlogRepositoryTest extends KernelTestCase
{

  use ResetDatabase;
  use Factories;

  public function testSomething(): void
  {
    //$kernel = self::bootKernel();

    // Инициализация ядра
    self::bootKernel();

    // Создание польз-ля для создания блогов
    $user = UserFactory::createOne();

    // создадим 7 тестовых блогов
    // App\Factory\BlogFactory
    BlogFactory::createOne(['user' => $user, 'title' => 'Something title to check']);
    BlogFactory::createMany(5, ['user' => $user]);

    // через $kernel->getContainer()->get получаем сервис - репозитарий блога
    //$kernel->getContainer()->set(BlogRepository::class, BlogRepository::class);
    $BlogRepository = static::getContainer()->get(BlogRepository::class);

    $blogs = $BlogRepository->getBlogs();
    //dd($blogs);

    // Проверим, что блогов действительно такое-то число - 6
    $this->assertCount(6, $blogs);

    // ПРоверим на идентичность
    //$this->assertSame('Something title', $blogs[0]->getTitle());
    $this->assertSame('Something title to check', $blogs[0]->getTitle());

    //$this->assertSame('test', $kernel->getEnvironment());
    // $routerService = static::getContainer()->get('router');
    // $myCustomService = static::getContainer()->get(CustomService::class);
  }
}
