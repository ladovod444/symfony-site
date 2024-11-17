<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class CurrenciesService
{

    public function __construct(private EntityManagerInterface $entityManager,
    private readonly HttpClient $httpClient)
    {

    }

    public function getCurrencies(): string
    {

        $url = 'https://www.cbr-xml-daily.ru/daily_json.js';
        $data = $this->httpClient->get($url);

        return $data;
    }
}