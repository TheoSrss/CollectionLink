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

    public function __construct(array $pictures = [])
    {
        $this->pictures = $pictures;
    }
}
