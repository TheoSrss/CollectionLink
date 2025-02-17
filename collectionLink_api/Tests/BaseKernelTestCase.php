<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
// use Liip\TestFixturesBundle\Test\FixturesTrait;

abstract class BaseKernelTestCase extends KernelTestCase
{
    // use FixturesTrait;

    protected ValidatorInterface $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->validator = static::getContainer()->get('validator');
    }

    protected function assertValidationErrors(object $entity, int $expectedErrors = 0): void
    {
        $errors = $this->validator->validate($entity);
        $messages = [];

        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($expectedErrors, $errors);
    }
}
