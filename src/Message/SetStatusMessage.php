<?php

namespace App\Message;

class SetStatusMessage
{
  public function __construct(
    private string $content,
  ) {
  }

  public function getContent(): string
  {
    return $this->content;
  }

  public function setContent(string $content): void {
    $this->content = $content;
  }

  public static function create(string $content): self {
    return new self($content);
  }
}