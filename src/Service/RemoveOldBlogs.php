<?php

namespace App\Service;

use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;

class RemoveOldBlogs
{
    public function __construct(
        private readonly BlogRepository $blogRepository,
        private readonly EntityManagerInterface $entityManager
    ) {

    }

    /**
     * @throws \DateMalformedStringException
     */
    public function getOldBlogs(int $month = 1): array
    {
        //$str_date = "2023-07-14 13:52:00";

        $date = new \DateTime();

        //$month_ago =
        // Пока указан 1 месяц
        // Далее сделать, чтобы было возможность указать в команде кол-во месяцев

        $month = -($month);
        $date->modify("$month month");
        //$date->setTimestamp(strtotime("14-02-2022, 13:52"));

        //return $this->blogRepository->findByDate($str_date);
        return $this->blogRepository->findByDate($date->format('Y-m-d H:i:s'));
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function removeOldBlogs(int $month = 1): int
    {
        $count = count($this->getOldBlogs($month));
        foreach ($this->getOldBlogs($month) as $blog) {
            $this->entityManager->remove($blog);
        }
        $this->entityManager->flush();
        return $count;
    }
}