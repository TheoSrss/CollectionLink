<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\PublicController;
use App\Repository\CollectionObjectRepository;
use App\State\CollectionPersister;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Trait\TimestampableTrait;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CollectionObjectRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['collection:read']],
    denormalizationContext: ['groups' => ['collection:write']],
    security: "is_granted('ROLE_USER')",
    operations: [
        new GetCollection(
            uriTemplate: '/collections',
        ),
        new Get(
            uriTemplate: '/collections/{id}',
        ),
        new Get(
            uriTemplate: '/collections/public/{slug}',
            controller: PublicController::class,
            read: false,
            name: 'public_collection',
            security: "is_granted('PUBLIC_ACCESS')"
        ),
        new Post(
            uriTemplate: '/collections',
            processor: CollectionPersister::class
        ),
        new Patch(
            uriTemplate: '/collections/{id}',
            security: "object.getUser() == user",
            processor: CollectionPersister::class
        ),
        new Delete(
            uriTemplate: '/collections/{id}',
            security: "object.getUser() == user"
        ),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['user' => 'exact'])]
#[ORM\HasLifecycleCallbacks]
class CollectionObject
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ApiProperty(identifier: true)]
    #[Groups(['collection:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 3, max: 30)]
    #[Groups(['collection:read', 'collection:write', 'user:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'collection')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['collection:write', 'collection:read'])]
    private ?User $user = null;

    /**
     * @var Collection<int, Collectable>
     */
    #[ORM\ManyToMany(targetEntity: Collectable::class)]
    #[Assert\Valid()]
    #[Assert\Count(
        min: 1,
    )]
    #[Groups(['collection:read', 'collection:write'])]
    private Collection $collectable;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['collection:write', 'collection:read'])]
    private ?string $description = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['collection:read'])]
    private ?string $slug = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $password = null;

    #[SerializedName('password')]
    #[Assert\Length(min: 8)]
    #[Groups(['collection:write'])]
    private string|null $plainPassword = null;

    #[Groups(['collection:write'])]
    private bool $changePassword = false;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    #[ORM\PrePersist]
    public function generateSlug(): void
    {
        if (!$this->slug) {
            $uuid = Uuid::v4();
            $this->slug = substr($uuid->toBase58(), 0, 15);
        }
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string|null $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function isPasswordValid(string|null $plainPassword): bool
    {
        if ($this->password === null) {
            return true;
        }
        if ($plainPassword === null) {
            return false;
        }
        return password_verify($plainPassword, $this->password);
    }

    public function getChangePassword(): bool
    {
        return $this->changePassword;
    }

    public function setChangePassword(bool $noPassword): self
    {
        $this->changePassword = $noPassword;
        return $this;
    }

    #[Groups(['collection:read'])]
    public function isPrivate(): bool
    {
        return $this->password !== null;
    }

    #[Assert\Callback]
    public function validatePlainPassword(ExecutionContextInterface $context, mixed $payload): void
    {
        if (($this->changePassword && empty($this->plainPassword)) && empty($this->password)) {
            $notBlankConstraint = new NotBlank();
            $context->buildViolation($notBlankConstraint->message)
                ->atPath('plainPassword')
                ->setCode($notBlankConstraint::IS_BLANK_ERROR)
                ->addViolation();
        }
    }
}
