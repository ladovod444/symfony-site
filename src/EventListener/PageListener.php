<?php

namespace App\EventListener;

use App\Entity\Page;
use App\Message\SetStatusMessage;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\MessageBusInterface;

//#[AsEntityListener(event: Events::postUpdate, method: 'preUpdate', entity: Blog::class)]
//class BlogListener
//{
//  // the entity listener methods receive two arguments:
//  // the entity instance and the lifecycle event
//  public function preUpdate(Blog $blog, PostUpdateEventArgs $event): void
//  {
//
//    $em = $event->getObjectManager();
//    // ... do something to notify the changes
//    dd($event);
//  }
//}

#[AsDoctrineListener(event: Events::postFlush, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::postPersist, priority: 500, connection: 'default')]
class PageListener
{

  private array $entites = [];

  public function __construct(private readonly MessageBusInterface $bus)
  {
  }
  // the listener methods receive an argument which gives you access to
  // both the entity object of the event and the entity manager itself
  public function postFlush(PostFlushEventArgs $args): void
  {

    //dd($this->entites);
    foreach ($this->entites as $entity) {
      $this->bus->dispatch(SetStatusMessage::create($entity->getId()));
      //$entity->setStatus(true);
    }
    //dd($args);
  }

  public function postPersist(PostPersistEventArgs $event): void
  {
    if ($event->getObject() instanceof Page) {
      $entity = $event->getObject();
      ///dd($args);
      $this->entites[$entity->getId()] = $entity;
      // И далее эти $this->entites можно увидеть в событии postFlush выше по коду
    }
  }
}