<?php

// src/Security/ApiKeyAuthenticator.php
namespace App\Security;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Firebase\JWT\SignatureInvalidException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiKeyAuthenticator extends AbstractAuthenticator
{
  public function __construct(private readonly UserRepository $userRepository,
  private string $publicKey) {

  }
  /**
   * Called on every request to decide if this authenticator should be
   * used for the request. Returning `false` will cause this authenticator
   * to be skipped.
   */
  public function supports(Request $request): ?bool
  {
    // "auth-token" is an example of a custom, non-standard HTTP header used in this application
    //return $request->headers->has('auth-token');
    return $request->headers->has('jwt-token');
  }

  public function authenticate(Request $request): Passport
  {
    //$apiToken = $request->headers->get('auth-token');
    $jwtToken = $request->headers->get('jwt-token');

    if (null === $jwtToken) {
      // The token header was empty, authentication fails with HTTP Status
      // Code 401 "Unauthorized"
      throw new CustomUserMessageAuthenticationException('No JWT token provided');
    }

    // В блоке try catch проверяем, на правильность jwt токена
    try {
      $decoded = (array)JWT::decode($jwtToken, new Key($this->publicKey, 'RS256'));
    }
    catch (SignatureInvalidException $exception) {
      throw new CustomUserMessageAuthenticationException($exception->getMessage());
    }

    //dd($decoded);

    $userId = $decoded['userId'];
    if (null === $userId) {
      // The token header was empty, authentication fails with HTTP Status
      // Code 401 "Unauthorized"
      throw new CustomUserMessageAuthenticationException('Invalid credentials1.');
    }

    $user = $this->userRepository->find($userId);
    //dd($user);
    if (null === $user) {
      throw new CustomUserMessageAuthenticationException('Invalid credentials2.');
    }

    // implement your own logic to get the user identifier from `$apiToken`
    // e.g. by looking up a user in the database using its API key
    //$userIdentifier = 1;

    //return new SelfValidatingPassport(new UserBadge($user->getId()));

    return new SelfValidatingPassport(new UserBadge($user->getUserIdentifier()));
    //return new SelfValidatingPassport(new UserBadge($userIdentifier));
  }

  public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
  {
    // on success, let the request continue
    return null;
  }

  public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
  {
    $data = [
      // you may want to customize or obfuscate the message first
      'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

      // or to translate this message
      // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
    ];

    return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
  }
}
