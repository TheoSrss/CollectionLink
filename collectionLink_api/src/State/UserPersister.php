<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\Security\CodeService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\MailerService;

/**
 * @implements ProcessorInterface<User, User|void>
 */
final readonly class UserPersister implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface          $processor,
        private UserPasswordHasherInterface $passwordHasher,
        private MailerService               $mailer,
        private CodeService                 $codeService
    ) {}

    /**
     * @param User $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        if ($data->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $data,
                $data->getPlainPassword()
            );
            $data->setPassword($hashedPassword);
            $data->eraseCredentials();
        }
        if (
            !in_array('byAdmin', $uriVariables) ||
            !$uriVariables['byAdmin']
        ) {
            $data->setRoles([]);
        }

        if (!in_array(User::ROLE_USER, $data->getRoles())) {
            $verificationCode = $this->codeService->generateNewCode();
            $data->setVerificationCode($verificationCode);
            $this->mailer->sendValidationCode($data, $verificationCode);
        }

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
