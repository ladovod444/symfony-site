<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home_conference')]
    public function index(): Response
    {
        $a = 1;
        return $this->render('home/index.html.twig', [
            'controller_name' => __CLASS__,
            'controller_pass' => __FILE__,
        ]);
    }
}
