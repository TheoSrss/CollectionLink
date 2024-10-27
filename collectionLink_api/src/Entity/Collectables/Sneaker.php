<?php

namespace App\Entity\Collectables;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Collectable;
use App\Entity\Collectables\Tools\Brand;
use App\Entity\Collectables\Tools\Color;
use App\Repository\SneakerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

//#[ORM\Entity(repositoryClass: SneakerRepository::class)]
//#[ApiResource]
class Sneaker extends Collectable
{

    /**
     * @var Collection<int, Brand>
     */
    #[ORM\ManyToMany(targetEntity: Brand::class)]
    private Collection $brand;

    /**
     * @var Collection<int, Color>
     */
    #[ORM\ManyToMany(targetEntity: Color::class)]
    private Collection $color;

    public function __construct()
    {
        $this->brand = new ArrayCollection();
        $this->color = new ArrayCollection();
    }

    /**
     * @return Collection<int, Brand>
     */
    public function getBrand(): Collection
    {
        return $this->brand;
    }

    public function addBrand(Brand $brand): static
    {
        if (!$this->brand->contains($brand)) {
            $this->brand->add($brand);
        }

        return $this;
    }

    public function removeBrand(Brand $brand): static
    {
        $this->brand->removeElement($brand);

        return $this;
    }

    /**
     * @return Collection<int, Color>
     */
    public function getColor(): Collection
    {
        return $this->color;
    }

    public function addColor(Color $color): static
    {
        if (!$this->color->contains($color)) {
            $this->color->add($color);
        }

        return $this;
    }

    public function removeColor(Color $color): static
    {
        $this->color->removeElement($color);

        return $this;
    }
}
