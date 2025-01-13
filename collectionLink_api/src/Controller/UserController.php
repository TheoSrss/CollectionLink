<?php

namespace App\Controller;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends GeneralController
{
    public function __construct(
        SerializerInterface $serializer,
    ) {
        parent::__construct($serializer);
    }

    public function __invoke(Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('User not authenticated');
        }

        return $this->jsonLd($user, 200, ['user:read']);
    }
}
