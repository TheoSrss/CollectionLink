<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\UserController;
use App\Dto\PasswordForgottenNewPasswordDto;
use App\Dto\PasswordForgottenRequestDto;
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
use App\Entity\Trait\TimestampableTrait;
use App\State\PasswordForgottenNewPasswordProcessor;
use App\State\PasswordForgottenRequestProcessor;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    operations: [
        new Get(),
        new Post(
            uriTemplate: '/register',
            security: "is_granted('PUBLIC_ACCESS')",
            validationContext: ['groups' => ['Default', 'user:create']],
            normalizationContext: ['groups' => ['user:read', 'user:create']],
            processor: UserPersister::class
        ),
        new Patch(
            processor: UserPersister::class
        ),
        new Get(
            uriTemplate: '/profile',
            controller: UserController::class . '::profile',
            security: "is_granted('IS_AUTHENTICATED_FULLY')",
            read: false,
            name: 'profile'
        ),
        new Post(
            uriTemplate: '/verify-email',
            controller: UserController::class . '::verifyEmailAddress',
            name: 'verify_email',
            security: "is_granted('IS_AUTHENTICATED_FULLY')"
        ),
        new Post(
            uriTemplate: '/forgot-password',
            security: "is_granted('PUBLIC_ACCESS')",
            input: PasswordForgottenRequestDto::class,
            processor: PasswordForgottenRequestProcessor::class,
            denormalizationContext: ['groups' => ['user:forgot-password']],
        ),
        new Post(
            uriTemplate: '/reset-password',
            security: "is_granted('PUBLIC_ACCESS')",
            input: PasswordForgottenNewPasswordDto::class,
            processor: PasswordForgottenNewPasswordProcessor::class,
            denormalizationContext: ['groups' => ['user:forgot-password']],
        ),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    security: "object === user "
)]
#[UniqueEntity('email')]
#[UniqueEntity('username')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableTrait;

    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    public static array $allRoles = [self::ROLE_USER, self::ROLE_ADMIN];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['user:read'])]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email()]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 100)]
    #[Groups(['user:read', 'user:write', 'user:forgot-password'])]
    private ?string $email = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 3, max: 30)]
    #[Groups(['user:read', 'user:write', 'collectable:read', 'collection:write', 'collection:read'])]
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
    private Collection $collection;

    #[ORM\Column(type: 'json')]
    #[Groups(['user:read'])]
    private array $roles = [];

    /**
     * @var Collection<int, Collectable>
     */
    #[ORM\OneToMany(targetEntity: Collectable::class, mappedBy: 'creator', orphanRemoval: true)]
    private Collection $collectablesCreated;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?string $verificationCode = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $passwordForgottenCode = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['user:read', 'user:write', 'collection:read'])]
    private ?string $bio = null;

    public function __construct()
    {
        $this->collection = new ArrayCollection();
        $this->collectablesCreated = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
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
            if ($collectablesCreated->getCreator() === $this) {
                $collectablesCreated->setCreator(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->username . ' | ' . $this->email;
    }

    public function getVerificationCode(): ?int
    {
        return $this->verificationCode;
    }

    public function setVerificationCode(int $verificationCode): self
    {
        $this->verificationCode = $verificationCode;

        return $this;
    }

    public function getPasswordForgottenCode(): ?int
    {
        return $this->passwordForgottenCode;
    }

    public function setPasswordForgottenCode(?int $code): self
    {
        $this->passwordForgottenCode = $code;

        return $this;
    }

    public function getBio(): ?string
    {
        return $this->bio;
    }

    public function setBio(?string $bio): self
    {
        $this->bio = $bio;

        return $this;
    }
}
