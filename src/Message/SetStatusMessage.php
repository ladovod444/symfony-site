<?php

namespace App\Message;

class SetStatusMessage
{
  public function __construct(
    private readonly string $content,
  ) {
  }

  public function getContent(): string
  {
    return $this->content;
  }
}