<?php

namespace App\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Repository\CollectionObjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class PublicController extends GeneralController
{
    public function __construct(
        private readonly CollectionObjectRepository $collectionRepo,
        SerializerInterface $serializer,
    ) {
        parent::__construct($serializer);
    }

    public function __invoke(Request $request, string $slug): Response
    {
        $collection = $this->collectionRepo->findOneBy(['slug' => $slug]);

        if (!$collection) {
            throw new NotFoundHttpException('Collection not found');
        }

        $password = $request->query->get('password');
        if (!$collection->isPasswordValid($password)) {
            throw new AccessDeniedHttpException('Invalid password');
        }

        return $this->jsonLd($collection, 200, ['collection:read']);
    }
}
