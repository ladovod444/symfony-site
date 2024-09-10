<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class OneMoreController extends AbstractController
{
    #[Route('/one/more', name: 'app_one_more')]
    public function index(): Response
    {
        return $this->render('one_more/index.html.twig', [
            'controller_name' => 'OneMoreController',
        ]);
    }
}
