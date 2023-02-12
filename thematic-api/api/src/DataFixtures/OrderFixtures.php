<?php

namespace App\DataFixtures;

use App\Entity\Meals;
use App\Entity\Order;
use App\Entity\Restaurant;
use App\Repository\MealsRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use PhpCsFixer\Fixer\DeprecatedFixerInterface;

class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private MealsRepository $mealsRepository,
    ) {
    }

    public function getDependencies(): array
    {
        return [
            RestaurantFixtures::class,
            UserFixtures::class,
            MealsFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $order = new Order;
        $order
            ->setStatus("paid")
            ->setClient($this->userRepository->findOneBy(['email' => 'customer1@gmail.com']))
            ->setRestaurantUser($this->userRepository->findOneBy(['email' => 'restaurant1@gmail.com']))
        ;

        $manager->persist($order);

        $order = new Order;
        $order
            ->setStatus("opened")
            ->setClient($this->userRepository->findOneBy(['email' => 'customer2@gmail.com']))
            ->setRestaurantUser($this->userRepository->findOneBy(['email' => 'restaurant1@gmail.com']))
        ;

        $manager->persist($order);

        $manager->flush();
    }
}
