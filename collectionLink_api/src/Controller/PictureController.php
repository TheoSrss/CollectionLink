<?php

namespace App\Controller;

use App\Repository\PictureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PictureController extends GeneralController
{
    #[Route('/pictures/{filename}', name: 'picture_view')]
    public function viewCollectablePicture(
        string $filename,
        Request $request,
        PictureRepository $pictureRepository
    ): Response {
        $picture = $pictureRepository->findByFileName($filename);

        if (!$picture) {
            throw new NotFoundHttpException('Picture not found');
        }

        $collectable = $picture->getCollectable();

        if (count($collectable->getCollections()) === 0) {
            throw new NotFoundHttpException('Picture not found');
        }
        $publicAccess = false;
        foreach ($collectable->getCollections() as $collection) {
            if (!$collection->isPrivate()) {
                $publicAccess = true;
            }
        }

        if (!$publicAccess) {
            $password = $request->get('password');
            if (!$collection->isPasswordValid($password)) {
                throw new AccessDeniedHttpException('Invalid password');
            }
        }

        $filePath = $this->getParameter('kernel.project_dir') . '/public/images/collectables/' . $picture->getName();

        if (!file_exists($filePath)) {
            throw new NotFoundHttpException('Picture not found');
        }

        $mimeType = mime_content_type($filePath);


        return new Response(file_get_contents($filePath), 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' .  $picture->getName(),
        ]);
    }
}
