<?php

namespace Tests\Web\Api;

use App\Factory\PageFactory;
use App\Factory\UserFactory;
use App\Repository\PageRepository;
use Tests\Helpers\WebTestCaseUnit;
use Helmich\JsonAssert\JsonAssertions;

class PageControllerTest extends WebTestCaseUnit
{
  use JsonAssertions;

  public function testIndex(): void
  {
    $client = static::createClient();

    // Создаем юзера для возможности создания тестовых pages
    $user = UserFactory::createOne();

    // Создаем 10 тестовых страниц
    PageFactory::createMany(10, ['user' => $user]);

    // Обращаемся к эндпоинту (\App\Controller\Api\PageController::add)
    $client->request('get', '/api/page');

    // получаем массив страниц
    $json = json_decode($client->getResponse()->getContent(), true);

    // Проверям кол-во
    $this->assertCount(10, $json);

    // Проверяем статус (успешность) запроса
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
  }

  public function testAdd(): void
  {
    $client = static::createClient();

    $user = UserFactory::createOne();
    $client->loginUser($user->_real());

    $pageJsonContent = '{
        "title": "N TEST Test page title23",
        "body": "TEST Test pm description3",
        "status": true,
        "author": "Mine",
        "tags": "tag1, tag2, tag3"
    }';

    $client->request('post', '/api/page', content: $pageJsonContent);
    $json = json_decode($client->getResponse()->getContent(), true);

    $this->assertJsonValueEquals($json, '$.title', 'N TEST Test page title23');
    $this->assertJsonValueEquals($json, '$.body', 'TEST Test pm description3');

    // Проверяем статус (успешность) запроса
    $this->assertEquals(201, $client->getResponse()->getStatusCode());
  }

  public function testDelete(): void {
    $client = static::createClient();
    $user = UserFactory::createOne();
    $page = PageFactory::createOne(['user' => $user]);
    $pageId = $page->getId();
    $client->loginUser($user->_real());

    $client->request('delete', '/api/page/' . $pageId);

    //dd(json_decode($client->getResponse()->getContent()));

    // Проверяем, что данной Page нет в БД
    $pageRepository = static::getContainer()->get(PageRepository::class);
    self::assertNull($pageRepository->find($pageId));

    // Проверяем ответ от эндпоинта Response::HTTP_NO_CONTENT - 204
    $this->assertEquals(204, $client->getResponse()->getStatusCode());
  }

  public function testUpdate(): void {
    $client = static::createClient();

    $user = UserFactory::createOne();

    $page = PageFactory::createOne(['user' => $user]);

    $pageJsonContentToUpdate = '{
        "title": "UPD N TEST Test page title23",
        "body": "UPD TEST Test pm body",
        "status": true,
        "author": "Mine",
        "tags": "tag1, tag2, tag3"
    }';

    $client->loginUser($user->_real());

    $client->request('put', '/api/page/' . $page->getId(), content: $pageJsonContentToUpdate);

    $json = json_decode($client->getResponse()->getContent(), true);

    //dd($json);
    
    // Проверяем, что в блоге обновлены title и description
    $this->assertJsonValueEquals($json, '$.title', 'UPD N TEST Test page title23');
    $this->assertJsonValueEquals($json, '$.body', 'UPD TEST Test pm body');
    $this->assertJsonValueEquals($json, '$.author', 'Mine');

    // Проверяем ответ от эндпоинта Response::HTTP_NO_CONTENT - 201
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertResponseIsSuccessful();
  }
}