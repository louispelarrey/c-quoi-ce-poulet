<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\TagRestaurant;
use App\Repository\TagRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TagFixtures extends Fixture
{
    private const TAGS = [
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

    public function __construct(
        private TagRepository $tagRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::TAGS as $tag) {
            $tagEntity = new Tag;
            $tagEntity->setName($tag);
            $manager->persist($tagEntity);
        }

        $manager->flush();
    }
}

