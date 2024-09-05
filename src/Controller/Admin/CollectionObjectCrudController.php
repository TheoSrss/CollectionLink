<?php

namespace App\Controller\Admin;

use App\Entity\CollectionObject;
use App\Service\EntityProcessorService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CollectionObjectCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly EntityProcessorService $customPersistService)
    {
    }

    public static function getEntityFqcn(): string
    {
        return CollectionObject::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            AssociationField::new('user')->autocomplete(),
            AssociationField::new('collectable')
                ->autocomplete()
                ->setQueryBuilder(
                    fn(QueryBuilder $queryBuilder) => $queryBuilder
                        ->andWhere('entity.public = :isPublic')
                        ->setParameter('isPublic', true)
                )
        ];
    }


    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->customPersistService->persist($entityInstance, 'create',);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->customPersistService->persist($entityInstance, 'update');
    }
}
