<?php

namespace App\Tests\Entity;

use App\Tests\BaseKernelTestCase;
use App\Tests\Entity\EntityFactory;
use PHPUnit\Framework\Attributes\DataProvider;

class CollectableTest extends BaseKernelTestCase
{

    public function testValidEntity(): void
    {
        $collectable = EntityFactory::createCollectable();
        $this->assertValidationErrors($collectable);
    }

    public static function invalidNameProvider(): array
    {
        return [
            'blank' => ['', 2],
            'min' => ['aa', 1],
            'max' => ['bigtextbigtextbigtextbigtextbigtextbigtext', 1]
        ];
    }

    #[DataProvider('invalidNameProvider')]
    public function testInvalidName(string $invalidName, int $totalErrors): void
    {
        $collectable = EntityFactory::createCollectable();

        $collectable->setName($invalidName);
        $this->assertValidationErrors($collectable, $totalErrors);
    }

    public function testInvalidCreator(): void
    {
        $collectable = EntityFactory::createCollectable();

        $collectable->setCreator(null);
        $this->assertValidationErrors($collectable, 1);
    }
}
