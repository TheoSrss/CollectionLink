<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    public function __invoke(Security $security): JsonResponse
       {
           $user = $security->getUser();

           if (!$user) {
               throw $this->createAccessDeniedException('User not authenticated');
           }

           return $this->json($user, 200, [], ['groups' => 'user:read']);
       }
}
