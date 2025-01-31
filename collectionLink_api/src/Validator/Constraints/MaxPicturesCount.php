<?php

namespace App\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class MaxPicturesCount extends Constraint
{
    public const MAX_PICTURES_ERROR = 'COLLECTABLE_MAX_PICTURES';

    public string $message = 'A collectable cannot have more than {{ limit }} pictures.';
    public int $limit = 5;

    protected const ERROR_NAMES = [
        self::MAX_PICTURES_ERROR => 'COLLECTABLE_MAX_PICTURES',
    ];
}
