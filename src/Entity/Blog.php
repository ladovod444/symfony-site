<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Dto\BlogDto;
use App\Repository\BlogRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use EasyCorp\Bundle\EasyAdminBundle\Field\Configurator\BooleanConfigurator;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Serializer\Attribute\Ignore;

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
  #[Groups(['only_api_blog'])]
  private ?int $id = null;

  #[Assert\NotBlank(message: 'Заголовок обязателен для заполнения')]
  #[ORM\Column(length: 255)]
  #[Groups(['blog:list', 'blog:item', 'only_api_blog'])]
  private ?string $title = null;

  #[Assert\NotBlank(message: 'Описание обязательно для заполнения')]
  #[ORM\Column(type: Types::TEXT)]
  #[Groups(['blog:list', 'blog:item', 'only_api_blog'])]
  private ?string $description = null;

  #[ORM\Column(type: Types::TEXT)]
  #[Groups(['blog:list', 'blog:item', 'only_api_blog'])]
  private ?string $text = null;

  #[ORM\Column]
  private ?string $status = null;

  #[ORM\ManyToOne(targetEntity: Category::class)]
  #[ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id')]
  #[Groups(['blog:list', 'blog:item', 'only_api_blog'])]
  private Category|null $category = null;

  #[Ignore]
  #[ORM\ManyToOne(targetEntity: User::class, cascade: ['persist'])]
  #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
  #[Groups(['blog:list', 'blog:item'])]
  private User|null $user = null;

//  #[Assert\NotBlank]
  #[ORM\Column(type: Types::SMALLINT, nullable: true)]
  private ?int $percent = null;

  #[ORM\OneToOne(mappedBy: 'blog', cascade: ['persist', 'remove'])]
  private BlogMeta|null $blogMeta = null;

  public function getBlogMeta(): ?BlogMeta
  {
    return $this->blogMeta;
  }

  public function setBlogMeta(?BlogMeta $blogMeta): static
  {
    $blogMeta?->setBlog($this);
    $this->blogMeta = $blogMeta;

    return $this;
  }

  public function __construct(UserInterface|User $user)
  {
    $this->user = $user;
    $this->comments = new ArrayCollection();
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
  #[ORM\ManyToMany(targetEntity: "Tag", cascade: ['persist']), ]
  #[Groups(['only_api_blog'])]
  private ArrayCollection|PersistentCollection $tags;

  #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
  private ?\DateTime $blockedAt = null;

  /**
   * @var Collection<int, BlogComment>
   */
  #[Ignore]
  #[ORM\OneToMany(
    targetEntity: BlogComment::class,
    mappedBy: 'blog',
    cascade: ['remove', 'persist'],
    orphanRemoval: true
  )]
  //#[ORM\OneToMany(targetEntity: BlogComment::class, mappedBy: 'blog', cascade: ['remove', 'persist'])]
  #[ORM\OrderBy(['id' => 'DESC'])]
  private Collection $comments;

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
  public function setBlockedAtValue(): void
  {
    //dd($this);
    if ($this->status === 'blocked' && !$this->blockedAt) {
    }
    $this->blockedAt = new \DateTime();
  }

//  #[ORM\PrePersist]
//  public function doOtherStuffOnPrePersist()
//  {
//    $this->title = 'changed from prePersist callback!';
//  }

  /**
   * @return Collection<int, BlogComment>
   */
  public function getComments(): Collection
  {
    return $this->comments;
  }

  public function addComment(BlogComment $comment): static
  {
    if (!$this->comments->contains($comment)) {
      $this->comments->add($comment);
      $comment->setBlog($this);
    }

    return $this;
  }

  public function removeComment(BlogComment $comment): static
  {
    if ($this->comments->removeElement($comment)) {
      // set the owning side to null (unless already changed)
      if ($comment->getBlog() === $this) {
        $comment->setBlog(null);
      }
    }

    return $this;
  }

  #[Ignore]
  public function getUserId(): int
  {
    return $this->getUser()->getId();
  }

  public static function createFromDto(UserInterface|User $user, BlogDto $blogDto): static
  {
    $blog = new self($user);
    $blog->setTitle($blogDto->title)
      ->setDescription($blogDto->description)
      ->setText($blogDto->text)
      ->setStatus($blogDto->status);

    return $blog;
    //->setTags($blogDto->tags);

//    $this->entityManager->persist($blog);
//    $this->entityManager->flush();
  }

  public static function updateFromDto(BlogDto $blogDto, Blog $blog): static
  {
    $blog->setTitle($blogDto->title)
      ->setDescription($blogDto->description)
      ->setText($blogDto->text)
      ->setStatus($blogDto->status);

    return $blog;
  }

}