<?php

namespace App\Filter;

use App\Entity\User;

class PageFilter
{
  public ?string $title = null;
  public ?string $body = null;

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

  public function getBody(): ?string
  {
    return $this->body;
  }

  public function setBody(?string $body): void
  {
    $this->body = $body;
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