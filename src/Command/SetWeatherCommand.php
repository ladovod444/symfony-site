<?php

namespace App\Command;

use App\Service\WeatherService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
  name: 'set-weather',
  description: 'Add a short description for your command',
)]
class SetWeatherCommand extends Command
{
  use LockableTrait;

  public function __construct(private WeatherService $weatherService)
  {
    parent::__construct();
  }

  protected function configure(): void
  {
    $this
      ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
      ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    if (!$this->lock()) {
      $output->writeln('The command is already running in another process.');
      return Command::SUCCESS;
    }
    $this->release();
    $this->weatherService->setWeather();
    $output->writeln('<info>Weather was set succcessfully ...</info>');
    return Command::SUCCESS;
  }
}
