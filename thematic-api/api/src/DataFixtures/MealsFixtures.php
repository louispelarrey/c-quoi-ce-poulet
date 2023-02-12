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
        $meal1 = new Meals;
        $meal1
            ->setName('Burger')
            ->setPrice(18)
            ->setPicturePath('string')
            ->setRestaurant($this->restaurantRepository->findOneBy(['name' => 'Schwartz Deli']));

        $manager->persist($meal1);

        $meal2 = new Meals;
        $meal2
            ->setName('Salade')
            ->setPrice(8)
            ->setPicturePath('string')
            ->setRestaurant($this->restaurantRepository->findOneBy(['name' => 'Schwartz Deli']));

        $manager->persist($meal2);

        $meal3 = new Meals;
        $meal3
            ->setName('Pizza')
            ->setPrice(12)
            ->setPicturePath('string')
            ->setRestaurant($this->restaurantRepository->findOneBy(['name' => 'Le petit resto']));

        $manager->persist($meal3);

        $manager->flush();
    }
}
