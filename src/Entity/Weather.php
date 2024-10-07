<?php

namespace App\Entity;

use App\Repository\WeatherRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: WeatherRepository::class)]
class Weather
{
  use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $temperature = null;

    #[ORM\Column]
    private ?int $elevation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getElevation(): ?int
    {
        return $this->elevation;
    }

    public function setElevation(int $elevation): static
    {
        $this->elevation = $elevation;

        return $this;
    }
}
