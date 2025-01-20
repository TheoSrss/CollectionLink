<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\PasswordForgottenRequestInput;
use App\Repository\UserRepository;
use App\Security\CodeService;
use App\Security\PasswordResetService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class PasswordForgottenRequestProcessor implements ProcessorInterface
{
    public function __construct(
        private PasswordResetService $passwordForgottenCodeService,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private CodeService $codeService
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        if (!$data instanceof PasswordForgottenRequestInput) {
            throw new \InvalidArgumentException('Invalid input.');
        }
        $user = $this->userRepository->findOneBy(['email' => $data->email]);
        if ($user) {
            $passwordForgottenCode = $this->codeService->generateNewCode();
            $user->setPasswordForgottenCode($passwordForgottenCode);
            $this->entityManager->persist($user);
            $this->passwordForgottenCodeService->sendPasswordResetEmail($user);
            $this->entityManager->flush();
        }

        return new JsonResponse([
            'message' => 'If this email exists, a recovery code has been sent.',
        ], 200);
    }
}
