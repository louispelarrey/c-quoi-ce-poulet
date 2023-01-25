<?php

namespace App\DataFixtures;

use App\Entity\Meals;
use App\Repository\RestaurantRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MealsFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private RestaurantRepository $restaurantRepository
    ) {
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            RestaurantFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $meal = new Meals;
        $meal
            ->setName('Burger')
            ->setPrice(18)
            ->setPicturePath('string')
            ->setRestaurant($this->restaurantRepository->findOneBy(['name' => 'Schwartz Deli']));

        $meal
            ->setName('Salade')
            ->setPrice(8)
            ->setPicturePath('string')
            ->setRestaurant($this->restaurantRepository->findOneBy(['name' => 'Schwartz Deli']));

        $manager->persist($meal);
        $manager->flush();
    }
}
