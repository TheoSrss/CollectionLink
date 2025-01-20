<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

class PasswordForgottenRequestInput
{
    #[Assert\NotBlank()]
    #[Assert\Email()]
    #[Assert\Length(min: 3, max: 30)]
    #[Groups(['user:forgot-password'])]
    public $email;
}
