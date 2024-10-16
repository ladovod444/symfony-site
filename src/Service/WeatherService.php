<?php

namespace App\Service;

use App\Entity\Weather;
use App\Repository\WeatherRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Monolog\Attribute\WithMonologChannel;

#[WithMonologChannel('weather')]
class WeatherService
{

  public function __construct(
    private WeatherHttpClient $weatherHttpClient,
    private string $api_url,
    private EntityManagerInterface $entityManager,
    private WeatherRepository $weatherRepository,
    private LoggerInterface $logger
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
    $this->logger->notice("Getting weather");
    $content = $this->weatherHttpClient->get($this->api_url);
    return $content ? json_decode($content, true) : [];
  }

  /**
   * @throws TransportExceptionInterface
   * @throws ServerExceptionInterface
   * @throws RedirectionExceptionInterface
   * @throws DecodingExceptionInterface
   * @throws ClientExceptionInterface
   */
  public function setWeather(): void
  {
    $weather_data = $this->getWeather();
    $weather = $this->weatherRepository->findAll();
    //dd($weather);

    $weather = count($weather) ? $weather[0] : new Weather();
    $weather->setTemperature($weather_data['current']['temperature_2m']);
    $weather->setElevation($weather_data['elevation']);

    $this->entityManager->persist($weather);
    $this->entityManager->flush();
    $this->logger->notice("Weather data was set");

  }
}