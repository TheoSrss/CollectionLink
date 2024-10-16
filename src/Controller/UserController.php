<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;

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
