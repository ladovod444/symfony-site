<?php

namespace App\Service;

use GuzzleHttp\Client;

class WeatherHttpClient
{
  public function get(string $url): string {
    $client = new Client();
    $response = $client->request(
      'GET',
      $url,
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
      $content = $response->getBody()->getContents();
    }

    return $content ?? '';
  }
}