<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Collectable;
use App\Entity\Picture;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use ApiPlatform\Validator\Exception\ValidationException;

final readonly class PictureProcessor implements ProcessorInterface
{

    public function __construct(
        private readonly Security  $security,
        private EntityManagerInterface $entityManager,
        private ValidatorInterface $validator
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

        foreach ($data->pictures as $file) {
            $picture = new Picture();
            $picture->setFile($file);
            $collectable->addPicture($picture);
        }

        $errors = $this->validator->validate($collectable);

        if ($errors->count() > 0) {
            throw new ValidationException($errors);
        }

        $this->entityManager->persist($collectable);
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Pictures uploaded',
        ], 200);
    }
}
