<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class IsValidPasswordForgottenCode extends Constraint
{
    public const USER_NOT_FOUND_ERROR = '1a2b3c4d-5e6f-7g8h-9i10-j11k12l13m14';
    public const CODE_INVALID_ERROR = '3c4d5e6f-7g8h-9i10-j11k-12l13m14n162';

    protected static $errorNames = [
        self::USER_NOT_FOUND_ERROR => 'USER_NOT_FOUND_ERROR',
        self::CODE_INVALID_ERROR => 'CODE_INVALID_ERROR',
    ];

    public string $messageUserNotFound = "This user does not exist.";
    public string $messageCodeInvalid = "This code is not correct.";

    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}
