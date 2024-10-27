<?php

namespace App\Service;

use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;

//Service used on EasyAdmin CRUD controller for access to entities Persisters .
class EntityProcessorService
{

    public function __construct(
        private readonly iterable $processors,
        private array             $processorMapping
    )
    {
    }

    public function persist($data, string $action, array $uriVariables = ['byAdmin'=>true], array $context = [])
    {
        $entityClass = get_class($data);
        if (!isset($this->processorMapping[$entityClass])) {
            throw new \RuntimeException("No processor mapped for entity $entityClass");
        }

        $processorClass = $this->processorMapping[$entityClass];

        foreach ($this->processors as $processor) {
            if ($processor instanceof $processorClass) {
                $operation = $this->createOperation($entityClass, $action);

                return $processor->process($data, $operation, $uriVariables, $context);
            }
        }

        throw new \RuntimeException("Processor $processorClass not found for entity $entityClass");
    }

    private function createOperation(string $entityClass, string $action): Post|Put
    {
        return match ($action) {
            'create' => new Post(class: $entityClass),
            'update' => new Put(class: $entityClass),
            default => throw new \InvalidArgumentException("Unsupported action: $action"),
        };
    }
}