<?php

namespace App\Entity;

use App\Repository\PageMetaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageMetaRepository::class)]
class PageMeta
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $author = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $description = null;

  #[ORM\Column(length: 255, nullable: true)]
  private ?string $keywords = null;

  #[ORM\OneToOne(targetEntity: Page::class)]
  #[ORM\JoinColumn(name: 'page_id', referencedColumnName: 'id')]
  private Page|null $page = null;

  public function getPage(): ?Page
  {
    return $this->page;
  }

  public function setPage(?Page $page): void
  {
    $this->page = $page;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getAuthor(): ?string
  {
    return $this->author;
  }

  public function setAuthor(?string $author): static
  {
    $this->author = $author;

    return $this;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(?string $description): static
  {
    $this->description = $description;

    return $this;
  }

  public function getKeywords(): ?string
  {
    return $this->keywords;
  }

  public function setKeywords(?string $keywords): static
  {
    $this->keywords = $keywords;

    return $this;
  }
}
