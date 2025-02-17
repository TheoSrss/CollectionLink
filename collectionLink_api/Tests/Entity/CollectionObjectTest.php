<?php

namespace App\Tests\Entity;

use App\Tests\BaseKernelTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class CollectionObjectTest extends BaseKernelTestCase
{
    public function testValidEntity(): void
    {
        $collection = EntityFactory::createCollectionObject();

        $this->assertValidationErrors($collection, 0);
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
        $collection = EntityFactory::createCollectionObject();
        $collection->setName($invalidName);

        $this->assertValidationErrors($collection, $totalErrors);
    }
}
