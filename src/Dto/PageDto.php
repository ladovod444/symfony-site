<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class PageDto
{
  public function __construct(
    #[Assert\NotBlank]
    public readonly ?string $title,
    #[Assert\NotBlank]
    public readonly ?string $body,
    #[Assert\NotBlank]
    public readonly ?bool $status,
    #[Assert\NotBlank]
    public readonly ?string $author,
    #[Assert\NotBlank]
    public readonly ?string $tags,)
  {
  }
}