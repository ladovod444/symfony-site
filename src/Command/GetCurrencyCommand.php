<?php

namespace App\Command;

use App\Service\CurrenciesService;
use Grpc\ChannelCredentials;
use Grpc\Currency\CurrencyCode;
use Grpc\Currency\CurrencyValue;
use Grpc\Currency\CurrentCurrencyClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:get-currency',
    description: 'Add a short description for your command',
)]
class GetCurrencyCommand extends Command
{
    public function __construct(private readonly CurrenciesService $currenciesService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
//            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('currency', null, InputOption::VALUE_OPTIONAL, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($currency = $input->getOption('currency')) {

//            $currency_data = $this->currenciesService->getCurrencies();
//            ///dd(json_decode($currency_data)->Valute);
//            $currency_code = strtoupper($currency);
//            $usd_data = json_decode($currency_data, true)['Valute'][$currency_code];
//            //dd($usd_data);

            $currencyClient = new CurrentCurrencyClient('127.0.0.1:9001', [
                'credentials' => ChannelCredentials::createInsecure(),
            ]);

            // Создаем message.
            $currencyCode = (new CurrencyCode())->setCode($currency);

            // Вызваем удаленную процедуру.
            /** @var CurrencyValue $currencyValue */
            [$currencyValue] = $currencyClient->report($currencyCode)->wait();

            //dd($currencyValue->getValue());

            $output->writeln(sprintf('<info>Currency is %s, value is %s</info>', $currency, $currencyValue->getValue()));
        }

        return Command::SUCCESS;
    }
}
