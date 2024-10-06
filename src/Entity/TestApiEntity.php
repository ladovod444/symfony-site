<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ConferenceRepository;

use App\Repository\TestApiEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TestApiEntityRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'test_entity:item']),
        new GetCollection(normalizationContext: ['groups' => 'test_entity:list'])
        ],
    //order: ['year' => 'DESC', 'city' => 'ASC'],
    paginationEnabled: false,
)]
class TestApiEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['test_entity:list', 'test_entity:item'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['test_entity:list', 'test_entity:item'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['test_entity:list', 'test_entity:item'])]
    private ?string $description = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
