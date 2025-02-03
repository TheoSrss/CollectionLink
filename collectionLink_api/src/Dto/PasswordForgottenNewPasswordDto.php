<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Validator\Constraints\IsValidPasswordForgottenCode;

#[IsValidPasswordForgottenCode()]
class PasswordForgottenNewPasswordDto
{
    #[Assert\NotBlank()]
    #[Assert\Type('integer')]
    #[Groups(['user:forgot-password'])]
    public $code;

    #[Assert\NotBlank()]
    #[Assert\Email()]
    #[Groups(['user:forgot-password'])]
    public $email;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 8)]
    #[Assert\Regex("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/")]
    #[Groups(['user:forgot-password'])]
    public $password;
}
