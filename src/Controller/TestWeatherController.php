<?php

namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TestWeatherController extends AbstractController
{

  public function __construct(private readonly WeatherService $weatherService)
  {

  }

  #[Route('/test-weather', name: 'test-weather')]
//  #[IsGranted('ROLE_SUPER_ADMIN', message: 'You are not allowed to access the admin dashboard.')]
  public function index(): Response
  {
//    $city = 'Tyumen';
//    $this->weatherService->getWeather($city);
//    $a = 1;

    return $this->render('best/index.html.twig', [
//      'controller_name' => 'BestController',
   ]);


//    return $this->render('best/index.html.twig', [
//      'controller_name' => 'BestController',
//    ]);
  }


}