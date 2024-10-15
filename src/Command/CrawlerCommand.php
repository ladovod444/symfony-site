<?php

namespace App\Command;

use App\Service\NewsGrabber;
use Monolog\Attribute\WithMonologChannel;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\Service\Attribute\Required;

#[AsCommand(
  name: 'blog:news:import',
  description: 'Import news from internet',
)]
class CrawlerCommand extends Command
{
  use LockableTrait;

  public function __construct(private readonly NewsGrabber $newsGrabber)
  {
    parent::__construct();
  }

  protected function configure(): void
  {
    $this->addArgument('count', InputArgument::OPTIONAL, 'Number of news')
      ->addOption('dryRun', null, InputOption::VALUE_OPTIONAL);
//        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
//        ;
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {

    if (!$this->lock()) {
      $output->writeln('The command is already running in another process.');
      return Command::SUCCESS;
    }

    //sleep(10000);

    //$io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    // Получение аргументов
    $count = $input->getArgument('count');
    //$dryRun =(bool)$input->getOption('dryRun');
    $dryRun = (bool)$input->getOption('dryRun');
//        echo $dryRun;
//        echo $count;
//        return Command::SUCCESS;


    $output->writeln('<info>Importing news ...</info>');
    $output->writeln('<error>Some error while importing news ...</error>');
    $output->writeln('<comment>Comment ...</comment>');

    // Если команда запущена в дебаг моде, то выводим логи в консоль
    if ($output->isVerbose()) { // таким образом определяем был ли указан verbose -v
      //dd($count);
      $logger = new ConsoleLogger($output);
      $this->newsGrabber->setLogger($logger);
    }

    // Иначе создается файл
    // #[Required] предполагает ,что метод им помеченный запуститься
    // И при указанном #[WithMonologChannel('parser')] будет использоваться именно parser
    $this->newsGrabber->importNews($count, $dryRun);
    //echo "test10000000\n";

    $this->release();
    return Command::SUCCESS;
  }
}
