<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\CollectableRepository;
use App\State\CollectablePersister;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CollectableRepository::class)]
//#[ORM\InheritanceType("JOINED")]
//#[ORM\DiscriminatorColumn(name: "type", type: "string")]
//#[ORM\DiscriminatorMap(["sneaker" => Sneaker::Class])]
#[ApiResource(
    operations: [
        new Get(),
        new Post(
            processor: CollectablePersister::class
        ),
        new Patch(
            security: "object.getCreator() == user and object.isPublic() == false",
        ),
        new Delete(
            security: "object.getCreator() == user and object.isPublic() == false",
        ),
    ],
    normalizationContext: ['groups' => ['collectable:read']],
    denormalizationContext: ['groups' => ['collectable:write']],
    security: "is_granted('ROLE_USER')"
)]
//abstract
class Collectable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['collectable:read', 'collectable:write','user:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'collectablesCreated')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['collectable:read'])]
    private ?User $creator = null;

    #[ORM\Column()]
    #[Groups(['collectable:read', 'collectable:write'])]
    private ?bool $public = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['collectable:read', 'collectable:write'])]
    private ?string $description = null;

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

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    public function isPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(bool $isPublic): static
    {
        $this->public = $isPublic;

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
