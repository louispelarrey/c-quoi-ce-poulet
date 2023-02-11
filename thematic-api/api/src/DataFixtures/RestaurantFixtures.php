<?php

namespace App\DataFixtures;

use App\Entity\Restaurant;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RestaurantFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UserRepository $userRepository)
    {

    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $restaurant = new Restaurant;
        $restaurant
            ->setName('Schwartz Deli')
            ->setAddress('1 rue de la petite maison')
            ->setPicturePath('string')
            ->setOpeningTime([
                "11:00",
                "12:00",
            ])
            ->setOwner($this->userRepository->findOneBy(['email' => 'user@user.com']))
            ->setIsActivated(true)
        ;
        $manager->persist($restaurant);
        $manager->flush();
    }
}