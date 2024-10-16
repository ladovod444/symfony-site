<?php

namespace App\Entity;

use App\Dto\BlogDto;
use App\Repository\BlogMetaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\PersistentCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlogMetaRepository::class)]
#[ORM\HasLifecycleCallbacks]
class BlogMeta
{
  use TimestampableEntity;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  private ?string $description = null;

  #[ORM\Column(length: 255)]
  private ?string $keywords = null;

  #[ORM\Column(length: 255)]
  private ?string $author = null;

  #[ORM\OneToOne(targetEntity: Blog::class)]
  #[ORM\JoinColumn(name: 'blog_id', referencedColumnName: 'id')]
  private Blog|null $blog = null;

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

  public function getAuthor(): ?string
  {
    return $this->author;
  }

  public function setAuthor(?string $author): static
  {
    $this->author = $author;

    return $this;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(?int $id): static
  {
    $this->id = $id;

    return $this;
  }

  public function getBlog(): ?Blog
  {
    return $this->blog;
  }

  public function setBlog(?Blog $blog): static
  {
    $this->blog = $blog;

    return $this;
  }


}