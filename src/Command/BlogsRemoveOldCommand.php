<?php

namespace App\Command;


use App\Service\RemoveOldBlogs;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:blogs:remove-old',
    description: 'Add a short description for your command',
)]
class BlogsRemoveOldCommand extends Command
{
    use LockableTrait;
    public function __construct(private RemoveOldBlogs $removeOldBlogs)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
//        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
//        ;
    }

    /**
     * @throws \DateMalformedStringException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        // @todo - сделать возможнго указывать месяц, то есть сколько месяцев - кол-во

        if (!$this->lock()) {
            $output->writeln('The command is already running in another process.');
            return Command::SUCCESS;
        }
        $this->release();
       // $blogs = $this->getOldBlogs->getOldBlogs();
        $this->removeOldBlogs->removeOldBlogs();
        //dd($blogs);
        $output->writeln('<info>Unactive blogs were removed ...</info>');
        return Command::SUCCESS;
    }
}
