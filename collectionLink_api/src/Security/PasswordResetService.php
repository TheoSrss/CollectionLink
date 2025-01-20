<?php

namespace App\Security;

use App\Entity\User;
use App\Service\MailerService;

class PasswordResetService
{

    public function __construct(
        private MailerService $mailer
    ) {}

    public function sendPasswordResetEmail(User $user)
    {
        $passwordForgottenCode = $user->getPasswordForgottenCode();
        $this->mailer->sendPasswordResetEmail($user, $passwordForgottenCode);
    }
}
