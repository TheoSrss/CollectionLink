<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CollectionObjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CollectionObjectRepository::class)]
#[ApiResource]
class CollectionObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'collection')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var Collection<int, Collectable>
     */
    #[ORM\ManyToMany(targetEntity: Collectable::class)]
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
