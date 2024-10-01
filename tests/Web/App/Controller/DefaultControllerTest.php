<?php

namespace App\Tests\Web\App\Controller;

use App\Factory\BlogFactory;
use App\Factory\UserFactory;
use App\Tests\Helpers\WebTestCaseUnit;
//use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
//use Zenstruck\Foundry\Test\Factories;
//use Zenstruck\Foundry\Test\ResetDatabase;

// Класс можно наследовть от вновь созданного
// tests/Helpers/WebTestCaseUnit
// для того, чтобы постоянно не приписывать трейты
//  ResetDatabase и Factories
//
class DefaultControllerTest extends WebTestCaseUnit
{


  public function testSomething(): void
  {

    $client = static::createClient();

    $user = UserFactory::createOne();

    // создадим 7 тестовых блогов
    // App\Factory\BlogFactory
    BlogFactory::createOne(['user' => $user, 'title' => 'Something title to check']);
    BlogFactory::createMany(6, ['user' => $user]);

    //$client->request('GET', '/');

    $crawler = $client->request('GET', '/');

    //dd($client->getResponse()->getContent());
     // Можно будет увидеть сгенерированные блоги

    $this->assertResponseIsSuccessful();
    //$this->assertSelectorTextContains('h1', 'Hello World');
    $this->assertSelectorTextContains('h1', 'Custom jumbotron');

    // Здесь проверим число сгенеренных блогов с теми, что на странице в контейнерах div.col-md-4.blog-item
    //$this->assertCount(6, $crawler->filter('div.col-md-4.blog-item')->count());

    // Идея в том, что 
    $this->assertCount(6, $crawler->filter('div.col-md-4.blog-item'));
  }
}
