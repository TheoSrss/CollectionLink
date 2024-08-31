<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    private static array $allRoles = [self::ROLE_USER, self::ROLE_ADMIN];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $username = null;

    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    /**
     * @var Collection<int, CollectionObject>
     */
    #[ORM\OneToMany(targetEntity: CollectionObject::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $collection;

    #[ORM\Column]
    private array $roles = [];

    public function __construct()
    {
        $this->collection = new ArrayCollection();
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
        // TODO: Implement eraseCredentials() method.
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }
}
