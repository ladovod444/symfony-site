<?php

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;


class ApiLoginController extends AbstractController
{
  #[Route('/api/login', 'api_login', methods: ['POST'])]
  public function index(#[CurrentUser] ?User $user): Response {

    //dd($user);
    return $this->json([
      'message' => 'Logged in successfully.',
      'file' => __FILE__
    ]);
  }
}