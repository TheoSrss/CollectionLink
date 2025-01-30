<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\Collectable;
use App\Entity\Picture;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final readonly class PictureProcessor implements ProcessorInterface
{

    public function __construct(
        private readonly Security  $security,
        private EntityManagerInterface $entityManager,
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): JsonResponse
    {
        $collectable = $this->entityManager->getRepository(Collectable::class)->find($uriVariables['id']);
        if (!$collectable) {
            throw new \Exception('Collectable not found');
        }
        if ($collectable->getCreator() !== $this->security->getUser()) {
            throw new \Exception('Access denied');
        }

        foreach ($data->files as $file) {
            $picture = new Picture();
            $picture->setFile($file);
            $collectable->addPicture($picture);
        }
        $this->entityManager->persist($collectable);
        $this->entityManager->flush();


        return new JsonResponse([
            'message' => 'Pictures uploaded',
        ], 200);
    }
}
