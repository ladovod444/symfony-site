<?php

namespace App\Filter;

use App\Entity\User;

class BlogFilter
{
  public ?string $title = null;
  public ?string $description = null;

  public function __construct(private ?User $user = null)
  {

  }

  public function getUser(): ?User
  {
    return $this->user;
  }

  public function setUser(?User $user): void
  {
    $this->user = $user;
  }
  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): void
  {
    $this->description = $description;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(?string $title): void
  {
    $this->title = $title;
  }
}