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
    description: 'Removes inactive blogs older than 1 month or more',
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
        $this
            //->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('month', null, InputOption::VALUE_OPTIONAL, 'Set months ago')
        ;
    }

    /**
     * @throws \DateMalformedStringException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        
        if (!$this->lock()) {
            $output->writeln('The command is already running in another process.');
            return Command::SUCCESS;
        }

        $month = (int) $input->getOption('month');

        $count = $this->removeOldBlogs->removeOldBlogs($month);
        //dd($blogs);
        $info = $count ? "Inactive blogs of count=$count were removed" : "No blogs were removed";
        $output->writeln("<info> $info ...</info>");

        $this->release();
        return Command::SUCCESS;
    }
}
