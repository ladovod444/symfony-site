<?php

namespace App\Resolver;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class BlogResolver
  //implements ValueResolverInterface, EventSubscriberInterface
{

  public static function getSubscribedEvents()
  {
    // TODO: Implement getSubscribedEvents() method.
  }

  public function resolve(Request $request, ArgumentMetadata $argument): iterable
  {
    // TODO: Implement resolve() method.
    return [];
  }
}