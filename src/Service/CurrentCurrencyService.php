<?php

namespace App\Service;

use Grpc\Currency\CurrencyCode;
use Grpc\Currency\CurrencyValue;
use Grpc\Currency\CurrentCurrencyInterface;
use Spiral\RoadRunner\GRPC;

class CurrentCurrencyService implements CurrentCurrencyInterface
{
    public function __construct(private CurrenciesService $currencies)
    {

    }

    public function report(GRPC\ContextInterface $ctx, CurrencyCode $in): CurrencyValue
    {
        // Получаем значение валюты
        $code = $in->getCode();

        $code = strtoupper($code);

        // обращаемся к "внешнему сервису"
        $currency_data = $this->currencies->getCurrencies();
        $valute_data = json_decode($currency_data, true)['Valute'][$code];

        // отправляем CurrencyValue message c полученным курсом валюты
        return (new CurrencyValue())->setValue($valute_data['Value']);
    }
}