<?php

namespace App\Entity;

use App\Dto\PageDto;
use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page
{
  use TimestampableEntity;

  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  #[Groups(["only_api_page", "only_doc_page"])]
  private ?string $title = null;

  #[ORM\Column(type: Types::TEXT, nullable: true)]
  #[Groups(["only_api_page", "only_doc_page"])]
  private ?string $body = null;

  #[ORM\Column]
  #[Groups(["only_doc_page"])]
  private ?bool $status = null;

  #[ORM\Column(length: 255)]
  #[Groups(["only_api_page", "only_doc_page"])]
  private ?string $author = null;

  #[ORM\ManyToOne(targetEntity: User::class)]
  #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
  private User|null $user = null;

  #[ORM\OneToOne(mappedBy: 'page', cascade: ['persist', 'remove'])]
  private PageMeta|null $pageMeta = null;

  public function getPageMeta(): ?PageMeta
  {
    return $this->pageMeta;
  }

  public function setPageMeta(?PageMeta $pageMeta): static
  {
    if ($pageMeta) {
      $pageMeta->setPage($this);
    }
    $this->pageMeta = $pageMeta;

    return $this;
  }


  public function __construct(UserInterface|User|null $user)
  {
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
  #[Groups(["only_api_page", "only_doc_page"])]
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
    return $this->tags ?? new ArrayCollection();
    //return $this->tags;
  }

  public function setTags(ArrayCollection|PersistentCollection $value)
  {
    $this->tags = $value;
  }

  /**
   * @return int
   *
   * "Вычисляемое значение"
   */
  #[Groups(["only_api_page"])]
  public function getUserInfo(): string
  {
    return $this->getUser()->getId() . ': ' .$this->getUser()->getEmail();
  }

  public static function createFromDto(UserInterface|User $user, PageDto $pageDto): static
  {
    $page = new self($user);
    $page->setTitle($pageDto->title);
    $page->setBody($pageDto->body);
    $page->setStatus($pageDto->status);
    $page->setAuthor($pageDto->author);

    return $page;
  }

  public static function updateFromDto(PageDto $pageDto, Page $page): static
  {
    $page->setTitle($pageDto->title)
      ->setBody($pageDto->body)
      ->setStatus($pageDto->status)
      ->setAuthor($pageDto->author);
    //->setTags($pageDto->tags);
    return $page;
  }

}