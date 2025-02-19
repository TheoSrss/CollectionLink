<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Collectable;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;

final readonly class CollectablePersister implements ProcessorInterface
{

    public function __construct(
        private ProcessorInterface $processor,
        private readonly Security  $security
    ) {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        dd(!in_array('byAdmin', $uriVariables), $uriVariables);
        if (
            !in_array('byAdmin', $uriVariables) ||
            !$uriVariables['byAdmin']
        ) {
            if ($data instanceof Collectable) {
                $user = $this->security->getUser();
                if ($user instanceof User) {
                    $data->setCreator($user);
                }
            }
        }
        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
