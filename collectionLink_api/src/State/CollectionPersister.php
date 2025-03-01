<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Collectable;
use App\Entity\CollectionObject;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CollectionPersister implements ProcessorInterface
{

    public function __construct(
        private ProcessorInterface $processor,
        private readonly Security  $security
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (
            !in_array('byAdmin', $uriVariables) ||
            !$uriVariables['byAdmin']
        ) {
            if ($data instanceof CollectionObject) {
                $user = $this->security->getUser();
                if ($user instanceof User) {
                    $data->setUser($user);
                }
                if ($data->getChangePassword() === true) {
                    if (is_null($data->getPlainPassword())) {
                        $data->setPassword(null);
                    } elseif (is_string($data->getPlainPassword())) {
                        $hashedPassword = password_hash($data->getPlainPassword(), PASSWORD_DEFAULT);
                        $data->setPassword($hashedPassword);
                    }
                }
            }
        }
        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
