<?php

namespace App\Filter;

class BlogFilter
{
  public ?string $title = null;

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(?string $title): void
  {
    $this->title = $title;
  }
}