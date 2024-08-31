<?php

namespace App\Entity\Collectables\Tools;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ColorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColorRepository::class)]
#[ApiResource]
class Color
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 7)]
    private ?string $hexa = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getHexa(): ?string
    {
        return $this->hexa;
    }

    public function setHexa(string $hexa): static
    {
        $this->hexa = $hexa;

        return $this;
    }
}
