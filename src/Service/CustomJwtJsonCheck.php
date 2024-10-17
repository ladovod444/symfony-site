<?php

namespace App\Service;

use App\Entity\User;
use Firebase\JWT\JWT;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CustomJwtJsonCheck
{

  public function __construct(private ParameterBagInterface $parameterBag) {

  }

  public function getJwt(?User $user): string {
    // Формируем payload
    $payload = [
      'userId' => $user->getId(),
      'iat' => time(), //1356999524, // время когда произошла авторизация
    ];

    // Формируем jwt
    $jwt = JWT::encode($payload, $this->parameterBag->get('app.jwt.privateKey'), 'RS256');

    return $jwt;
  }
}