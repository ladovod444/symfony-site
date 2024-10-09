<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class BlogDto
{
//  public readonly ?string $title;
//  public readonly ?string $description;
//  public readonly ?string $text;
//  public readonly ?string $status;
//  public readonly ?string $tags;

  public function __construct(
    #[Assert\NotBlank]
    public readonly ?string $title,
    #[Assert\NotBlank]
    public readonly ?string $description,
    #[Assert\NotBlank]
    public readonly ?string $text,
    #[Assert\NotBlank]
    public readonly ?string $status,
    #[Assert\NotBlank]
    public readonly ?string $tags,)
  {
  }

  /**
   * @param string|null $title
   */
//  public function setTitle(?string $title): void
//  {
//    $this->title = $title;
//  }
//
//  public function setDescription(?string $description): void
//  {
//    $this->description = $description;
//  }
//
//  public function setText(?string $text): void
//  {
//    $this->text = $text;
//  }
//
//  public function setStatus(?string $status): void
//  {
//    $this->status = $status;
//  }
//
//  public function setTags(?string $tags): void
//  {
//    $this->tags = $tags;
//  }

}