<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class test extends GeneralController
{
    public function __construct(
        SerializerInterface $serializer,
        private UserRepository $userRepository
    ) {
        parent::__construct($serializer);
    }

    public function profile(Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('User not authenticated');
        }

        return $this->JsonResponse($user, 200, ['user:read']);
    }

    public function verifyEmailAddress(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {

        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();
        $code = $data['code'] ?? null;

        if (in_array(User::ROLE_USER, $user->getRoles())) {
            return new JsonResponse(['error' => 'User already verified.'], 400);
        }

        if (!$code) {
            return new JsonResponse(['error' => 'Code is required.'], 400);
        }

        if (!$user || $user->getVerificationCode() != $code) {
            return new JsonResponse(['error' => 'Invalid verification code.'], 400);
        }

        $user->setRoles([User::ROLE_USER]);

        $em->persist($user);
        $em->flush();

        return new JsonResponse(['message' => 'Account successfully verified.'], 200);
    }
}
