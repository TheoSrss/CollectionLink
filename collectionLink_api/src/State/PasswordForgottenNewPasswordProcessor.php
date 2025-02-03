<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\PasswordForgottenNewPasswordDto;
use App\Repository\UserRepository;
use App\Security\CodeService;
use App\Security\PasswordResetService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class PasswordForgottenNewPasswordProcessor implements ProcessorInterface
{
    public function __construct(
        private PasswordResetService $passwordForgottenCodeService,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private CodeService $codeService,
        private UserPasswordHasherInterface $passwordHasher,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        if (!$data instanceof PasswordForgottenNewPasswordDto) {
            throw new \InvalidArgumentException('Invalid input.');
        }
        $user = $this->userRepository->findOneBy(['email' => $data->email]);
        if ($user) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user,
                $data->password
            );
            $user->setPassword($hashedPassword);
            $user->setPasswordForgottenCode(null);
            $user->eraseCredentials();

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }

        return new JsonResponse([
            'message' => 'If all is correct, the password is updated.',
        ], 200);
    }
}
