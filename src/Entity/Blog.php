<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use EasyCorp\Bundle\EasyAdminBundle\Field\Configurator\BooleanConfigurator;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'blog:item']),
        new GetCollection(normalizationContext: ['groups' => 'blog:list'])
    ],
    order: ['id' => 'DESC',],
    paginationEnabled: true,
)]

// https://symfony-blog.ddev.site/api/blogs?page=6

class Blog
{
  use TimestampableEntity;
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Assert\NotBlank(message: 'Заголовок обязателен для заполнения')]
  #[ORM\Column(length: 255)]
  #[Groups(['blog:list', 'blog:item'])]
  private ?string $title = null;

  #[Assert\NotBlank(message: 'Описание обязательно для заполнения')]
  #[ORM\Column(type: Types::TEXT)]
  #[Groups(['blog:list', 'blog:item'])]
  private ?string $description = null;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $text = null;

  #[ORM\Column]
  private ?string $status = null;

  #[ORM\ManyToOne(targetEntity: Category::class)]
  #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
  private Category|null $category = null;

  #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'])]
  #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
  #[Groups(['blog:list', 'blog:item'])]
  private User|null $user = null;

//  #[Assert\NotBlank]
  #[ORM\Column(type: Types::SMALLINT, nullable: true)]
  private ?int $percent = null;

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


  #[ORM\ManyToOne(targetEntity: BlogCollection::class)]
  #[ORM\JoinColumn(name: 'collection_id', referencedColumnName: 'id')]
  private BlogCollection|null $blog_collection = null;


  #[ORM\JoinTable(name: 'tags_to_blog')]
  #[ORM\JoinColumn(name: 'blog_id', referencedColumnName: 'id')]
  #[ORM\InverseJoinColumn(name: 'tag_id', referencedColumnName: 'id', unique: true)]
  #[ORM\ManyToMany(targetEntity: "Tag", cascade: ['persist']),]

  private ArrayCollection|PersistentCollection $tags;

  #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
  private ?\DateTime $blockedAt = null;

  public function getBlockedAt(): ?\DateTime
  {
    return $this->blockedAt;
  }

  public function setBlockedAt(?\DateTime $blockedAt): static
  {
    $this->blockedAt = $blockedAt;

    return $this;
  }

  public function setText(?string $text): static
  {
    $this->text = $text;

    return $this;
  }

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

  public function getText(): ?string
  {
    return $this->text;
  }

  public function getDescription(): ?string
  {
    return $this->description;
  }

  public function setDescription(string $text): static
  {
    $this->description = $text;

    return $this;
  }

  public function getStatus(): ?string
  {
    return $this->status;
  }

  public function setStatus(string $status): static
  {
    $this->status = $status;

    return $this;
  }

  public function getPercent(): ?string
  {
    return $this->percent;
  }

  public function setPercent(?string $percent): void
  {
    $this->percent = $percent;
  }

  public function getCategory(): ?Category
  {
    return $this->category;
  }

  public function setCategory(Category $category): static
  {
    $this->category = $category;

    return $this;
  }

  public function getBlogCollection(): ?BlogCollection
  {
    return $this->blog_collection;
  }

  public function setBlogCollection(BlogCollection $blog_collection): static
  {
    $this->blog_collection = $blog_collection;

    return $this;
  }


  public function getTags(): ArrayCollection|PersistentCollection
  {
    return $this->tags;
  }

  public function setTags(ArrayCollection $value)
  {
    $this->tags = $value;
  }

  public function addTag(Tag $tag): void
  {
    //$tag->addArticle($this); // synchronously updating inverse side
    $this->tags[] = $tag;
  }

#[ORM\PreUpdate]
public function setBlockedAtValue():void
{
  //dd($this);
  if ($this->status === 'blocked' && !$this->blockedAt) {}
  $this->blockedAt = new \DateTime();
}

//  #[ORM\PrePersist]
//  public function doOtherStuffOnPrePersist()
//  {
//    $this->title = 'changed from prePersist callback!';
//  }

}