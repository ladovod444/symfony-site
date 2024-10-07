<?php

namespace Tests\Kernel\Service;

use App\Factory\UserFactory;
use App\Repository\BlogRepository;
use App\Repository\UserRepository;
use App\Service\HttpClient;
use App\Service\NewsGrabber;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class NewsGrabberTest extends KernelTestCase
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

    // Создаем Mock для userRepository.
    $userRepository = $this->createMock(UserRepository::class);
    // $user - это тот, который был создан выше - $user = UserFactory::createOne();
    //$userRepository->method('find')->willReturn($user);
    $userRepository->method('find')->willReturn($user->_real());
    // Подставить в контейнер
    static::getContainer()->set(UserRepository::class, $userRepository);


    // Создаем Mock для  private readonly HttpClient $httpClient.
    $httpClient = $this->createMock(HttpClient::class);
    // Вызвать метод
//    $httpClient
//      ->method('get')
//      ->with('https://www.engadget.com/news/')
//      ->willReturn(file_get_contents('tests/DataProvider/index.html'));
    $httpClient
      ->method('get')
      ->willReturnCallback(function (string $url) {
        //dd($url); // Получим https://www.engadget.com/news/"
        static $index = 0;
        if ($url == "https://www.engadget.com/news/") {
          return file_get_contents('tests/DataProvider/index.html');
        } else {
          return file_get_contents('tests/DataProvider/news' . ++$index . '.html');
        }

      });
    // Подставить в контейнер.
    static::getContainer()->set(HttpClient::class, $httpClient);


    $newsGrabber = static::getContainer()->get(NewsGrabber::class);
    assert($newsGrabber instanceof NewsGrabber);

    // Создаем Mock для logger.
    $logger = $this->createMock(LoggerInterface::class);

    $newsGrabber->setLogger($logger)->importNews(); // здесь пока 4
     // see src/Service/NewsGrabber.php, LINE 84

    // Далее пока проверим кол-во постов блога.
    $blogRepository = static::getContainer()->get(BlogRepository::class);
    assert($blogRepository instanceof BlogRepository);

    //$blogs = $blogRepository->getBlogs();
    $blogs = $blogRepository->findAll();
    //self::assertCount(6, $blogs);
    // Пока чтобы не создавать все новости со страницы https://www.engadget.com/news/
    // сделаем 4.
    self::assertCount(4, $blogs);

    // Сравним тайтлы и содержимое.
    $items = [];
    foreach ($blogs as $blog) {
      $items[] = [
        'title' => $blog->getTitle(),
        'text' => $blog->getDescription(),
      ];
    }
    file_put_contents('tests/DataProvider/expected.json', json_encode($items));

    // Здесь проверяем, сравниваем получаемые новости с содержимым expected.json.
    self::assertSame(
      json_decode(file_get_contents('tests/DataProvider/expected.json'), true),
      $items);


    //echo json_encode($items); exit();

  }
}
