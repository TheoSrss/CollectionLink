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
use App\Dto\CollectablePictureRequestDto;

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

        if ($operation->getName() === 'pictures_upload') {
            return $this->uploadPictures($data, $collectable);
        }

        if ($operation->getName() === 'pictures_delete') {
            return $this->deletePictures($data, $collectable);
        }

        return new JsonResponse([
            'Not found'
        ], 404);
    }

    private function uploadPictures(CollectablePictureRequestDto $pictureDto, Collectable $collectable): JsonResponse
    {
        foreach ($pictureDto->pictures as $file) {
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

    private function deletePictures(CollectablePictureRequestDto $pictureDto, Collectable $collectable): JsonResponse
    {
        foreach ($pictureDto->picturesIdsToDelete as $pictureId) {
            $picture = $collectable->getPictures()->filter(function (Picture $p) use ($pictureId) {
                return $p->getId() === $pictureId;
            })->first();

            if ($picture) {
                $collectable->removePicture($picture);
                $this->entityManager->remove($picture);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Pictures deleted',
        ], 200);
    }
}
