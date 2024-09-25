<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class BestController extends AbstractController
{
    #[Route('/homepage', name: 'homepage')]
    #[IsGranted('ROLE_SUPER_ADMIN', message: 'You are not allowed to access the admin dashboard.')]
    public function index(): Response
    {
        return $this->render('best/index.html.twig', [
            'controller_name' => 'BestController',
        ]);
    }
}
