<?php

namespace App\DataFixtures;

use App\Entity\TagRestaurant;
use App\Entity\User;
use App\Repository\TagRestaurantRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TagRestaurantFixtures extends Fixture
{
    public function __construct(
        private TagRestaurantRepository $tagRestaurantRepository,
        private UserPasswordHasherInterface $passwordHasher
    ){}

    public function load(ObjectManager $manager): void
    {
        $tagRestaurants = [
            "Italien",
            "Français",
            "Chinois",
            "Japonais",
            "Indien",
            "Mexicain",
            "Espagnol",
            "Brésilien",
            "Américain",
            "Grec",
            "Turc",
            "Libanais",
            "Senegalais",
            "Marocain",
            "Algérien",
            "Russie"
        ];
        foreach($tagRestaurants as $tagRestaurant) {
            $tagRestaurantEntity = new TagRestaurant;
            $tagRestaurantEntity->setName($tagRestaurant);
            $manager->persist($tagRestaurantEntity);
        }

        $manager->flush();
    }
}
