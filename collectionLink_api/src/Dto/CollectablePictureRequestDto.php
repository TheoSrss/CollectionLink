<?php

namespace App\Dto;

use App\Entity\Collectable;
use Symfony\Component\Validator\Constraints as Assert;

readonly class CollectablePictureRequestDto
{
    #[Assert\All([
        new Assert\File(
            maxSize: '5M',
            mimeTypes: ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'],
        )
    ])]
    public array $pictures;

    #[Assert\All([
        new Assert\Type('integer')
    ])]
    public array $picturesIdsToDelete;

    public function __construct(array $pictures = [], array $picturesIdsToDelete = [])
    {
        $this->pictures = $pictures;
        $this->picturesIdsToDelete = $picturesIdsToDelete;
    }
}
