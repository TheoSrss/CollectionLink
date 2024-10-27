<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\CollectionObjectRepository;
use App\State\CollectablePersister;
use App\State\CollectionPersister;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CollectionObjectRepository::class)]
#[ApiResource(
    uriTemplate: '/collections{/id}',
    operations: [
        new GetCollection(
            uriTemplate: '/collections',
        ),
        new Get(),
        new Post(
            processor: CollectionPersister::class
        ),
        new Patch(
            security: "object.getUser() == user",
        ),
        new Delete(
            security: "object.getUser() == user",
        ),
    ],
    normalizationContext: ['groups' => ['collection:read']],
    denormalizationContext: ['groups' => ['collection:write']],
    security: "is_granted('ROLE_USER')",

)] class CollectionObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['collection:read', 'collection:write', 'user:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'collection')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['collection:write'])]
    private ?User $user = null;

    /**
     * @var Collection<int, Collectable>
     */
    #[ORM\ManyToMany(targetEntity: Collectable::class)]
    #[Groups(['collection:read', 'collection:write'])]
    private Collection $collectable;

    public function __construct()
    {
        $this->collectable = new ArrayCollection();
    }

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Collectable>
     */
    public function getCollectable(): Collection
    {
        return $this->collectable;
    }

    public function addCollectable(Collectable $collectable): static
    {
        if (!$this->collectable->contains($collectable)) {
            $this->collectable->add($collectable);
        }

        return $this;
    }

    public function removeCollectable(Collectable $collectable): static
    {
        $this->collectable->removeElement($collectable);

        return $this;
    }
}
