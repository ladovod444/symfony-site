<?php

namespace App\MessageHandler;

use App\Message\CheckUniqueTextJob;
use App\Repository\BlogRepository;
use App\Service\CheckUniqueText;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CheckUniqueTextJobHandler
{

  public function __construct(private readonly EntityManagerInterface $entityManager,
  private readonly CheckUniqueText $checkUniqueText,
  private readonly BlogRepository $blogRepository,) {

  }

  public function __invoke(CheckUniqueTextJob $checkUniqueTextJob): void
  {
    // ... do some work - like sending an SMS message!
    //dd($checkUniqueTextJob);
    $blogId = (int) $checkUniqueTextJob->getContent();
    $blog = $this->blogRepository->find($blogId);

    $unique_percent = $this->checkUniqueText->checkUniqueText($blog->getDescription());
    $blog->setPercent($unique_percent);

    $this->entityManager->flush();
  }
}