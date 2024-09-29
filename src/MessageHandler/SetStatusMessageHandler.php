<?php

namespace App\MessageHandler;

use App\Message\CheckUniqueTextJob;
use App\Message\SetStatusMessage;
use App\Repository\BlogRepository;
use App\Repository\PageRepository;
use App\Service\CheckUniqueText;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SetStatusMessageHandler
{

  public function __construct(private readonly EntityManagerInterface $entityManager,
  //private readonly SetStatusMessage $checkUniqueText,
  private readonly PageRepository $pageRepository,) {

  }

  public function __invoke(SetStatusMessage $setStatusMessage): void
  {
    // ... do some work - like sending an SMS message!
    //dd($checkUniqueTextJob);
    $pageId = (int) $setStatusMessage->getContent();
    $page = $this->pageRepository->find($pageId);

    // Set status true
    $page->setStatus(true);

    $this->entityManager->flush();
  }
}