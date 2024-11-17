<?php

namespace App\Command;

use Grpc\ChannelCredentials;
use Grpc\Digits\DigitsGeneratorClient;

use Grpc\Digits\DigitsGenerated;
use Grpc\Digits\DigitsNumber;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:nums-generate',
    description: 'Add a short description for your command',
)]
class NumsGenerateCommand extends Command
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
           // ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('--number-of-digits', null, InputOption::VALUE_OPTIONAL, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        if ($num_of_git = $input->getOption('number-of-digits')) {
            $digitsGeneratorClient = new DigitsGeneratorClient('127.0.0.1:9001', [
                'credentials' => ChannelCredentials::createInsecure(),
            ]);

            $digitsNumber = (new DigitsNumber())->setDigits($num_of_git);

            /** @var DigitsGenerated $digitsGenerated */
            [$digitsGenerated] = $digitsGeneratorClient->generator($digitsNumber)->wait();

            $output->writeln($digitsGenerated->getDigits());

        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
