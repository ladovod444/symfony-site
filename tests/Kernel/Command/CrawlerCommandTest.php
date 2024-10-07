<?php

namespace Tests\Kernel\Command;

use App\Repository\UserRepository;
use App\Service\NewsGrabber;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Tester\CommandTester;

final class CrawlerCommandTest extends KernelTestCase
{
  public function testExecute(): void
  {
    self::bootKernel();
    $application = new Application(self::$kernel);

    // Создаем Mock для NewsGrabber
    $newsGrabber = self::createMock(NewsGrabber::class);
    // Подставить в контейнер
    static::getContainer()->set(NewsGrabber::class, $newsGrabber);

    // Создаем Mock для ConsoleLogger
    $consoleLogger = self::createMock(ConsoleLogger::class);
    // Подставить в контейнер
    static::getContainer()->set(ConsoleLogger::class, $consoleLogger);

    // Говорим phpunit'у сколько раз метод setLogger должен быть запущен - once.
    $newsGrabber
      ->expects($this->once())
      ->method('setLogger')
      //->with($consoleLogger)
      ->willReturn($newsGrabber);

    // Говорим phpunit'у сколько раз метод importNews должен быть запущен - once.
    $newsGrabber->expects($this->once())
      ->method('importNews')
      ->with(null, null);


    $command = $application->find('blog:news:import');
    $commandTester = new CommandTester($command);
    $commandTester->execute([]);
//    $commandTester->execute([
//      // pass arguments to the helper
//      'count' => 3,
//
//      // prefix the key with two dashes when passing options,
//      // e.g: '--some-option' => 'option_value',
//      // use brackets for testing array value,
//      // e.g: '--some-option' => ['option_value'],
//    ]);

    $commandTester->assertCommandIsSuccessful();

    // the output of the command in the console
//    $output = $commandTester->getDisplay();
//    dd($output);
//    $this->assertStringContainsString('Count: 3', $output);

    // ...
  }

  public function testExecuteParameters(): void
  {
    self::bootKernel();
    $application = new Application(self::$kernel);

    // Создаем Mock для NewsGrabber
    $newsGrabber = self::createMock(NewsGrabber::class);
    // Подставить в контейнер
    static::getContainer()->set(NewsGrabber::class, $newsGrabber);

    // Создаем Mock для ConsoleLogger
    $consoleLogger = self::createMock(ConsoleLogger::class);
    // Подставить в контейнер
    static::getContainer()->set(ConsoleLogger::class, $consoleLogger);

    // Говорим phpunit'у сколько раз метод setLogger должен быть запущен - once.
    $newsGrabber
      ->expects($this->once())
      ->method('setLogger')
      ->willReturn($newsGrabber);

    // Говорим phpunit'у сколько раз метод importNews должен быть запущен - once.
    $newsGrabber->expects($this->once())
      ->method('importNews')
      ->with(10, true);

    $command = $application->find('blog:news:import');
    $commandTester = new CommandTester($command);
    $commandTester->execute([
      'count' => 10,
      '--dryRun' => true,
    ]);
    $commandTester->assertCommandIsSuccessful();

    // the output of the command in the console
//    $output = $commandTester->getDisplay();
//    dd($output);
//    $this->assertStringContainsString('Count: 3', $output);

    // ...
  }
}