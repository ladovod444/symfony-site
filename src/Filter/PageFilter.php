<?php

namespace App\Filter;

class PageFilter
{
  public ?string $title = null;
  public ?string $body = null;

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