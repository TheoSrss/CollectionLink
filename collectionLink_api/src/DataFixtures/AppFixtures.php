<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Collectable;
use App\Entity\CollectionObject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;
use Symfony\Component\Filesystem\Filesystem;
use App\Entity\Picture;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $filesystem = new Filesystem();

        $uploadDir = __DIR__ . '/../../public/images/collectables';

        if (!$filesystem->exists($uploadDir)) {
            $filesystem->mkdir($uploadDir, 0777);
        }

        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setUsername($faker->userName)
                ->setEmail($faker->unique()->safeEmail)
                ->setRoles([User::ROLE_USER])
                ->setPassword($this->passwordHasher->hashPassword($user, 'password123'));

            $manager->persist($user);
            $users[] = $user;
        }

        for ($i = 0; $i < 20; $i++) {
            $collectable = new Collectable();
            $collectable->setName($faker->word)
                ->setDescription($faker->words(rand(15, 50), true))
                ->setPublic($faker->boolean)
                ->setCreator($faker->randomElement($users));

            $manager->persist($collectable);


            for ($j = 0; $j < rand(1, 3); $j++) {
                $imagePath = __DIR__ . '/images/sample' . rand(1, 5) . '.jpg'; // Images factices à copier
                $newFilename = uniqid() . '.jpg';
                $filesystem->copy($imagePath, $uploadDir . '/' . $newFilename);

                $picture = new Picture();
                $picture->setName($newFilename);
                $picture->setCollectable($collectable);
                $picture->setCreatedAtValue(new \DateTimeImmutable());

                $manager->persist($picture);
            }
        }

        $manager->flush();
        $manager->clear();

        $users = $manager->getRepository(User::class)->findAll();
        $collections = [];

        for ($i = 0; $i < 10; $i++) {
            $user = $faker->randomElement($users);
            if ($user) {
                $collection = (new CollectionObject())
                    ->setName($faker->word)
                    ->setUser($user)
                    ->setDescription($faker->words(rand(15, 50), true));

                for ($j = 0; $j < rand(0, 4); $j++) {
                    $collectable = $faker->randomElement($user->getCollectablesCreated()->toArray());
                    if (!in_array($collectable, $collection->getCollectable()->toArray())) {
                        $collection->addCollectable($collectable);
                    }
                }

                $manager->persist($collection);
                $collections[] = $collection;
            }
        }
        $manager->flush();
    }
}
