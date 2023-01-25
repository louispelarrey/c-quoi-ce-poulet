<?php

namespace App\DataFixtures;

use App\Entity\MealOrder;
use App\Repository\MealsRepository;
use App\Repository\OrderRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MealOrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private OrderRepository $orderRepository,
        private MealsRepository $mealsRepository
    ) {
    }

    public function getDependencies(): array
    {
        return [
            OrderFixtures::class,
            MealsFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
    }
}
