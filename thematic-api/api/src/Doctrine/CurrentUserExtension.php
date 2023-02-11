<?php
// api/src/Doctrine/CurrentUserExtension.php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Order;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;

final class CurrentUserExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []): void
    {
        $this->addWhere($queryBuilder, $resourceClass);
    }

    private function addWhere(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (Order::class !== $resourceClass || $this->security->isGranted('ROLE_ADMIN') || null === $user = $this->security->getUser()) {
            return;
        }


        $rootAlias = $queryBuilder->getRootAliases()[0];

        switch ($user->getRoles()[0]) {
            case 'ROLE_USER':
                $queryBuilder->andWhere(sprintf('%s.client = :current_user', $rootAlias));
                break;
            case 'ROLE_DELIVERY':
                $queryBuilder->andWhere(sprintf('%s.deliverer = :current_user', $rootAlias));
                break;
            case 'ROLE_RESTAURANT':
                $queryBuilder->andWhere(sprintf('%s.restaurantUser = :current_user', $rootAlias));
                break;
        }
        /**
         * @var User $user
         */
        $queryBuilder->setParameter('current_user', $user->getId());
    }
}
