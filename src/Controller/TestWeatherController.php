<?php

namespace App\Controller;

use App\Service\RemoveOldBlogs;
use App\Service\WeatherService;
use Doctrine\ORM\EntityManagerInterface;
use Grpc\ChannelCredentials;
use Grpc\Shortener\LongUrl;
use Grpc\Shortener\ShortUrl;
use Grpc\Shortener\UrlShortenerClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestWeatherController extends AbstractController
{

  public function __construct(private readonly WeatherService $weatherService, private readonly RemoveOldBlogs $removeOldBlogs)
  {

  }

  #[Route('/test-weather', name: 'test-weather')]
//  #[IsGranted('ROLE_SUPER_ADMIN', message: 'You are not allowed to access the admin dashboard.')]
  public function index(): Response
  {
//    $city = 'Tyumen';
//    $this->weatherService->getWeather($city);
//    $a = 1;

    $weather = $this->weatherService->getWeather();

    return new Response(json_encode($weather));

//    return $this->render('best/index.html.twig', [
////      'controller_name' => 'BestController',
//   ]);


//    return $this->render('best/index.html.twig', [
//      'controller_name' => 'BestController',
//    ]);
  }

    #[Route('/test-blogs', name: 'test-blogs')]
//  #[IsGranted('ROLE_SUPER_ADMIN', message: 'You are not allowed to access the admin dashboard.')]
    public function index2(): Response {

        $urlShortenerClient = new UrlShortenerClient('127.0.0.1:9001', [
            'credentials' => ChannelCredentials::createInsecure(),
        ]);

        $longUrl = (new LongUrl())->setUrl('https://example.com/long-url');

        /** @var ShortUrl $shortUrl */
        [$shortUrl] =  $urlShortenerClient->shorten($longUrl)->wait();

        dd($shortUrl);

        //$output->writeln('Short url: ' . $shortUrl->getUrl());

        return new Response(json_encode([]));

//      $oldBlogs = $this->removeOldBlogs->getOldBlogs();
//      dd($oldBlogs);

    }
}