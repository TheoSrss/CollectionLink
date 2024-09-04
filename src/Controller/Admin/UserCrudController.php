<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Service\EntityProcessorService;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly EntityProcessorService $customPersistService)
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {

        return [
            TextField::new('email'),
            TextField::new('username'),
            TextField::new('plainPassword')
                ->onlyOnForms()
                ->setRequired($pageName === Crud::PAGE_NEW),
            ChoiceField::new('roles')
                ->setChoices(array_combine(User::$allRoles, User::$allRoles))
                ->allowMultipleChoices()
                ->renderAsBadges(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->customPersistService->persist($entityInstance,'create',);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->customPersistService->persist($entityInstance,'update');
    }
}
