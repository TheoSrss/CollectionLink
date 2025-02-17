<?php

namespace App\Tests\Entity;

use App\Tests\BaseKernelTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class UserTest extends BaseKernelTestCase
{
    public function testValidEntity(): void
    {
        $user = EntityFactory::createUser();
        $this->assertValidationErrors($user, 0);
    }

    public static function invalidEmailProvider(): array
    {
        return [
            'invalid' => ['invalidemail'],
            'blank' => [''],
            'max' => ['bigemailbigemailbigemailbigemailbigemailbigemailbibigemailbigemailbigemailbigemailbigemailbigemail@gmail.com']
        ];
    }

    #[DataProvider('invalidEmailProvider')]
    public function testInvalidEmail(string $invalidEmail): void
    {
        $user = EntityFactory::createUser();

        $user->setEmail($invalidEmail);
        $this->assertValidationErrors($user, 1);
    }

    public static function invalidUsernameProvider(): array
    {
        return [
            'blank' => ['', 2],
            'min' => ['aa', 1],
            'max' => ['bigtextbigtextbigtextbigtextbigtextbigtext', 1],
        ];
    }

    #[DataProvider('invalidUsernameProvider')]
    public function testInvalidUsername(string $invalidUsername, int $totalErrors): void
    {
        $user = EntityFactory::createUser();

        $user->setUsername($invalidUsername);
        $this->assertValidationErrors($user, $totalErrors);
    }

    public static function invalidPasswordProvider(): array
    {
        return [
            'too short' => ['aa', 2],
            'no uppercase' => ['abcdefgh1!', 1],
            'no number' => ['Abcdefgh!', 1],
            'no special character' => ['Abcdefgh1', 1],
            'empty' => ['', 1],
        ];
    }


    #[DataProvider('invalidPasswordProvider')]
    public function testInvalidPassword(string $invalidPassword, int $totalErrors): void
    {
        $user = EntityFactory::createUser();

        $user->setPlainPassword($invalidPassword);
        $this->assertValidationErrors($user, $totalErrors);
    }

    public function testUniqueEmail()
    {
        // $this->loadFixtureFiles([dirname(__DIR__) . '/Fixtures/users.yaml']);
        // $this->assertHasErrors(EntityFactory::createUser()->setEmail('test@gmail.com'), 1);
    }
}
