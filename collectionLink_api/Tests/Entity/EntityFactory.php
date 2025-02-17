<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Collectable;
use App\Entity\CollectionObject;

class EntityFactory
{
    public static function createUser(array $data = []): User
    {
        $user = new User();
        $user->setEmail($data['email'] ?? 'test@gmail.com');
        $user->setUsername($data['username'] ?? 'username');
        $user->setRoles($data['roles'] ?? [User::ROLE_USER]);

        return $user;
    }

    public static function createCollectable(array $data = []): Collectable
    {
        $collectable = new Collectable();
        $collectable->setName($data['name'] ?? 'Default Collectable');
        $collectable->setDescription($data['description'] ?? 'Default Description');
        $collectable->setCreator($data['creator'] ?? self::createUser());

        return $collectable;
    }

    public static function createCollectionObject(array $data = []): CollectionObject
    {
        $collection = new CollectionObject();
        $collection->setName($data['name'] ?? 'Default Collectable');
        $collection->setDescription($data['description'] ?? 'Default Description');
        $user = $data['creator'] ?? self::createUser();
        $collection->setUser($user);
        for ($i = 0; $i < rand(1, 3); $i++) {
            $collection->addCollectable(self::createCollectable(['creator' => $user]));
        }

        return $collection;
    }
}
