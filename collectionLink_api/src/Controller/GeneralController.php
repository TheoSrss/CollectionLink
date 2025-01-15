<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class GeneralController extends AbstractController
{
    public function __construct(
        protected SerializerInterface $serializer
    ) {}

    protected function JsonResponse(
        mixed $data,
        int $status = 200,
        array $groups = [],
        array $context = [],
        array $headers = []
    ): Response {

        $defaultContext = [
            'groups' => $groups,
        ];

        if (is_object($data)) {

            $defaultContext['resource_class'] = get_class($data);
        }

        $context = array_merge($defaultContext, $context);

        $jsonContent = $this->serializer->serialize($data, 'jsonld', $context);

        $defaultHeaders = [
            'Content-Type' => 'application/ld+json',
        ];

        $headers = array_merge($defaultHeaders, $headers);

        return new Response($jsonContent, $status, $headers);
    }
}
