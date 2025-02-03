<?php

namespace App\Entity;

use App\Repository\PictureRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trait\TimestampableTrait;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Attribute\Groups;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\State\PictureProcessor;
use App\Dto\CollectablePictureRequestDto;
use ApiPlatform\Metadata\Get;

#[ORM\Entity(repositoryClass: PictureRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
        new Post(
            uriTemplate: '/collectables/{id}/pictures',
            processor: PictureProcessor::class,
            input: CollectablePictureRequestDto::class,
            inputFormats: ['multipart' => ['multipart/form-data']]
        )
    ],
)]
class Picture
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'collectables_pictures', fileNameProperty: 'name', size: 'fileSize')]
    private ?File $file = null;

    #[ORM\Column(length: 255)]
    #[Groups(['collectable:read'])]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $fileSize = null;

    #[ORM\ManyToOne(inversedBy: 'pictures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Collectable $collectable = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if ($file) {
            $this->updatedAt = new \DateTimeImmutable();
        }

        if ($file && !$this->getId()) {
            $this->createdAt  = new \DateTimeImmutable();
        }
    }

    public function getFile(): ?File
    {
        return $this->file;
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

    public function getFileSize(): ?int
    {
        return $this->fileSize;
    }

    public function setFileSize(?int $fileSize): self
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    public function getCollectable(): ?Collectable
    {
        return $this->collectable;
    }

    public function setCollectable(?Collectable $collectable): static
    {
        $this->collectable = $collectable;

        return $this;
    }

    #[Groups(['collectable:read'])]
    public function getUrl(): ?string
    {
        $filenameWithoutExtension = pathinfo($this->getName(), PATHINFO_FILENAME);

        return '/pictures/' . $filenameWithoutExtension;
    }
}
