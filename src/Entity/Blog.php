<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use EasyCorp\Bundle\EasyAdminBundle\Field\Configurator\BooleanConfigurator;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
class Blog
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[Assert\NotBlank(message: 'Заголовок обязателен для заполнения')]
  #[ORM\Column(length: 255)]
  private ?string $title = null;

  #[Assert\NotBlank(message: 'Описание обязательно для заполнения')]
  #[ORM\Column(type: Types::TEXT)]
  private ?string $description = null;

  #[ORM\Column(type: Types::TEXT)]
  private ?string $text = null;

  #[ORM\Column]
  private ?bool $status = null;

  #[ORM\ManyToOne(targetEntity: Category::class)]
  #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
  private Category|null $category = null;

  #[ORM\ManyToOne(targetEntity: User::class)]
  #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
  private User|null $user = null;

//  #[Assert\NotBlank]
  #[ORM\Column(type: Types::SMALLINT, nullable: true)]
  private ?string $percent = null;

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

  public function getStatus(): ?bool
  {
    return $this->status;
  }

  public function setStatus(bool $status): static
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

}