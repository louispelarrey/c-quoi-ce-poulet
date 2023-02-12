<?php

namespace App\DataFixtures;

use App\Entity\MealOrder;
use App\Entity\TagRestaurant;
use App\Repository\MealsRepository;
use App\Repository\OrderRepository;
use App\Repository\RestaurantRepository;
use App\Repository\TagRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MealOrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private MealsRepository $mealsRepository,
        private OrderRepository $orderRepository)
    {

    }
    public function getDependencies(): array
    {
        return [
            MealsFixtures::class,
            OrderFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $mealOrder = new MealOrder;
        $mealOrder
            ->setMeal($this->mealsRepository->findOneBy(['name' => 'Burger']))
            ->setOrderEntity($this->orderRepository->findOneBy(['id' => 1]));

        $manager->persist($mealOrder);

        $mealOrder = new MealOrder;
        $mealOrder
            ->setMeal($this->mealsRepository->findOneBy(['name' => 'Salade']))
            ->setOrderEntity($this->orderRepository->findOneBy(['id' => 1]));

        $manager->persist($mealOrder);

        $mealOrder = new MealOrder;
        $mealOrder
            ->setMeal($this->mealsRepository->findOneBy(['name' => 'Burger']))
            ->setOrderEntity($this->orderRepository->findOneBy(['id' => 2]));

        $manager->persist($mealOrder);

        $manager->flush();
    }
}

