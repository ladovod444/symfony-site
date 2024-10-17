<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Service\CustomJwtJsonCheck;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class ApiLoginController extends AbstractController
{
  #[NoReturn]
  #[Route('/api/login', 'api_login_json', methods: ['POST'])]
  public function index(#[CurrentUser] ?User $user, CustomJwtJsonCheck $customJwtJsonCheck): Response {
    if (null === $user) {
      return $this->json([
        'message' => 'missing creds',
        'file' => __FILE__
      ]/*, Response::HTTP_UNAUTHORIZED*/);
    }
    
    return $this->json([
      'message' => 'Result: Success',
      'jwt' => $customJwtJsonCheck->getJwt($user)
    ]);
  }
}