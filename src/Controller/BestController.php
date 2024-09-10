<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BestController extends AbstractController
{
    #[Route('/best', name: 'app_best')]
    public function index(): Response
    {
        return $this->render('best/index.html.twig', [
            'controller_name' => 'BestController',
        ]);
    }
}
