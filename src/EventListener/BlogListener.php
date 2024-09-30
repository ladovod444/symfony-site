<?php

// src/EventListener/UserChangedNotifier.php
namespace App\EventListener;

use App\Entity\Blog;
use App\Message\CheckUniqueTextJob;
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

class BlogListener
{

  private array $entites = [];

  public function __construct(private readonly MessageBusInterface $bus){}
  // the listener methods receive an argument which gives you access to
  // both the entity object of the event and the entity manager itself
  public function postFlush(	PostFlushEventArgs $args): void
  {

    //dd($this->entites);
    foreach ($this->entites as $entity){
      $this->bus->dispatch(new CheckUniqueTextJob($entity->getId()));
    }
    //dd($args);
//    $entity = $args->getObject();
//    if (!$entity instanceof Blog) {
//
//      $this->bus->dispatch(new CheckUniqueTextJob($entity->getId()));
//      return;
//    }

//    $entityManager = $args->getObjectManager();
//    // ... do something with the Product entity
  }

  public function postPersist(PostPersistEventArgs $event): void {

    if ($event->getObject() instanceof Blog) {
      $entity = $event->getObject();
      ///dd($args);
      $this->entites[$entity->getId()] = $entity;
       // И далее эти $this->entites можно увидеть в событии postFlush выше по коду
    }


  }
}