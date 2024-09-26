<?php

namespace App\Entity;

use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $title = null;

  #[ORM\Column(type: Types::TEXT, nullable: true)]
  private ?string $body = null;

  #[ORM\Column]
  private ?bool $status = null;

  #[ORM\Column(length: 255)]
  private ?string $author = null;

  #[ORM\ManyToOne(targetEntity: User::class)]
  #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
  private User|null $user = null;

  public function __construct(UserInterface|User $user) {
    $this->user = $user;
  }

  public function getUser(): ?User
  {
    return $this->user;
  }

  public function setUser(?User $user): void
  {
    $this->user = $user;
  }


  #[ORM\JoinTable(name: 'tags_to_page')]
  #[ORM\JoinColumn(name: 'page_id', referencedColumnName: 'id')]
  #[ORM\InverseJoinColumn(name: 'tag_id', referencedColumnName: 'id', unique: true)]
  #[ORM\ManyToMany(targetEntity: "Tag", cascade: ['persist']), ]
  private ArrayCollection|PersistentCollection $tags;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getTitle(): ?string
  {
    return $this->title;
  }

  public function setTitle(string $title): static
  {
    $this->title = $title;

    return $this;
  }

  public function getBody(): ?string
  {
    return $this->body;
  }

  public function setBody(?string $body): static
  {
    $this->body = $body;

    return $this;
  }

  public function isStatus(): ?bool
  {
    return $this->status;
  }

  public function setStatus(bool $status): static
  {
    $this->status = $status;

    return $this;
  }

  public function getAuthor(): ?string
  {
    return $this->author;
  }

  public function setAuthor(string $author): static
  {
    $this->author = $author;

    return $this;
  }

  public function getTags(): ArrayCollection|PersistentCollection
  {
    return $this->tags;
  }

  public function setTags(ArrayCollection|PersistentCollection $value)
  {
    $this->tags = $value;
  }
}