<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{

  public function __construct(
    private HttpClientInterface $httpClient,
    private string $api_url,
  ){

  }

  /**
   * @throws RedirectionExceptionInterface
   * @throws DecodingExceptionInterface
   * @throws ClientExceptionInterface
   * @throws TransportExceptionInterface
   * @throws ServerExceptionInterface
   */
  public function getWeather(): array
  {

//    $user_ip = getenv('REMOTE_ADDR');
//    $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
//    $g = 1;

    //$url = "https://api.open-meteo.com/v1/forecast?latitude=52.52&longitude=13.41&current=temperature_2m,wind_speed_10m&hourly=temperature_2m,relative_humidity_2m,wind_speed_10m"
    $response = $this->httpClient->request(
      'GET',
      $this->api_url,
      [
        'query' => [
          'latitude'=> 57.1615,
          'longitude'=> 65.5346,
          'current'=>'temperature_2m',

        ],
      ],
    );

    $statusCode = $response->getStatusCode();
    if ($statusCode === 200) {
      $content = $response->toArray();
    }

    return $content ?? [];
  }
}