<?php

namespace Tests\Web\Api;
//Tests\Kernel\App\Repository;

use App\Factory\BlogFactory;
use App\Factory\UserFactory;
use App\Repository\BlogRepository;
use Helmich\JsonAssert\JsonAssertions;
use Tests\Helpers\WebTestCaseUnit;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCaseUnit
{
  use JsonAssertions;
  public function testIndex(): void
  {

    $client = static::createClient();
    $user = UserFactory::createOne();

    // Создадим
    BlogFactory::createMany(10, ['user' => $user]);

    //$crawler = $client->request('GET', '/api/blog');
    $client->request('GET', '/api/blog');

    $this->assertResponseIsSuccessful();

    //dump($client->getResponse()->getContent());

//    $this->assertSelectorTextContains('h1', 'Custom jumbotron');
//    $this->assertCount(6, $crawler->filter('div.col-md-4.blog-item'));
  }

  public function testAdd(): void
  {
    $client = static::createClient();
    $user = UserFactory::createOne();

    $client->loginUser($user->_real());

    $content = '{
        "title": "BBNNN Test pm title23",
        "description": "Test pm description3",
        "text": "Test pm text",
        "status": "pending",
        "tags": "tag1, tag2, tag3"
    }';

    $client->request('POST', '/api/blog', content: $content);

    //dump($client->getResponse()->getContent());
    $json = json_decode($client->getResponse()->getContent(), true);

    //self::assertSame('BBNNN Test pm title23', $json['title']);

    $this->assertJsonValueEquals($json, '$.title', 'BBNNN Test pm title23');
    $this->assertJsonValueEquals($json, '$.description', 'Test pm description3');

    $this->assertResponseIsSuccessful();

  }

  public function testDelete(): void
  {
    $client = static::createClient();
    $user = UserFactory::createOne();

    $blog = BlogFactory::createOne(['user' => $user]);

    $client->loginUser($user->_real());

    $blogId = $blog->getId();
    //$client->request('DELETE', '/api/blog/id/' . $blog->getId());
    $client->request('DELETE', '/api/blog/' . $blog->getId());
    //dd($client->getResponse()->getContent());

    //dd($blogId);
    $blogRepository = static::getContainer()->get(BlogRepository::class);

    //dd($blog);

    $t_blog = $blogRepository->find($blogId);
    //dd($t_blog);

    self::assertNull($blogRepository->find($blogId));
    $this->assertResponseIsSuccessful();

  }

  public function testUpdate(): void
  {
    $client = static::createClient();
    $user = UserFactory::createOne();

    $client->loginUser($user->_real());

    // Создаем пост блога
    $blog = BlogFactory::createOne(['user' => $user]);

    // задаем контент для обновления
    $content = '{
        "title": "UPDATED Test pm title23",
        "description": "UPDATED Test pm description3",
        "text": "Test pm text",
        "status": "pending",
        "tags": "tag1, tag2, tag3"
    }';

    // Обновляем
    $client->request('PUT', '/api/blog/' . $blog->getId(), content: $content);

    //dump($client->getResponse()->getContent());
    // Получаем обновленное содержимое
    $json = json_decode($client->getResponse()->getContent(), true);

    //dd($json);

    //self::assertSame('BBNNN Test pm title23', $json['title']);

    // Проверяем, что в блоге обновлены title и description
    $this->assertJsonValueEquals($json, '$.title', 'UPDATED Test pm title23');
    $this->assertJsonValueEquals($json, '$.description', 'UPDATED Test pm description3');

    $this->assertResponseIsSuccessful();

  }

}