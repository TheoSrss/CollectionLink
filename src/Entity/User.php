<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use App\State\UserPersister;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\UserController;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Get(),
        new Post(
            security: "is_granted('PUBLIC_ACCESS')",
            validationContext: ['groups' => ['Default','user:create']],
            processor: UserPersister::class
        ),
        new Patch(
            processor: UserPersister::class
        ),
        new Get(
            uriTemplate: '/profile',
            controller: UserController::class,
            security: "is_granted('ROLE_USER')",
            read: false,
            name: 'profile'
        ),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    security: "object === user "
)]
#[UniqueEntity('email')]
#[UniqueEntity('username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    public static array $allRoles = [self::ROLE_USER, self::ROLE_ADMIN];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email()]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 100)]
    #[Groups(['user:read', 'user:write'])]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank()]
    #[Groups(['user:read', 'user:write', 'collectable:read','collection:write'])]
    private ?string $username = null;

    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    #[Assert\NotBlank(groups: ['user:create'])]
    #[SerializedName('password')]
    #[Assert\Length(min: 8)]
    #[Assert\Regex("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/")]
    #[Groups(['user:write'])]
    private ?string $plainPassword = null;

    /**
     * @var Collection<int, CollectionObject>
     */
    #[ORM\OneToMany(targetEntity: CollectionObject::class, mappedBy: 'user', orphanRemoval: true)]
    #[Groups(['user:read'])]
    private Collection $collection;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var Collection<int, Collectable>
     */
    #[ORM\OneToMany(targetEntity: Collectable::class, mappedBy: 'creator', orphanRemoval: true)]
    #[Groups(['user:read'])]
    private Collection $collectablesCreated;

    public function __construct()
    {
        $this->collection = new ArrayCollection();
        $this->collectablesCreated = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return Collection<int, CollectionObject>
     */
    public function getCollection(): Collection
    {
        return $this->collection;
    }

    public function addCollection(CollectionObject $collection): static
    {
        if (!$this->collection->contains($collection)) {
            $this->collection->add($collection);
            $collection->setUser($this);
        }

        return $this;
    }

    public function removeCollection(CollectionObject $collection): static
    {
        if ($this->collection->removeElement($collection)) {
            // set the owning side to null (unless already changed)
            if ($collection->getUser() === $this) {
                $collection->setUser(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $accepted = array_filter($roles, function ($role) {
            return in_array($role, self::$allRoles);
        });

        if (!in_array(self::ROLE_USER, $accepted)) {
            $accepted[] = self::ROLE_USER;
        }
        $this->roles = array_unique($accepted);

        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    /**
     * @return Collection<int, Collectable>
     */
    public function getCollectablesCreated(): Collection
    {
        return $this->collectablesCreated;
    }

    public function addCollectablesCreated(Collectable $collectablesCreated): static
    {
        if (!$this->collectablesCreated->contains($collectablesCreated)) {
            $this->collectablesCreated->add($collectablesCreated);
            $collectablesCreated->setCreator($this);
        }

        return $this;
    }

    public function removeCollectablesCreated(Collectable $collectablesCreated): static
    {
        if ($this->collectablesCreated->removeElement($collectablesCreated)) {
            // set the owning side to null (unless already changed)
            if ($collectablesCreated->getCreator() === $this) {
                $collectablesCreated->setCreator(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->username.' | '.$this->email;
    }
}
