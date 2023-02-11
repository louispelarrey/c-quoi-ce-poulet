<?php

namespace App\DataFixtures;

use App\Entity\TagRestaurant;
use App\Repository\RestaurantRepository;
use App\Repository\TagRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TagRestaurantFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private TagRepository $tagRepository,
        private RestaurantRepository $restaurantRepository)
    {

    }
    public function getDependencies(): array
    {
        return [
            TagFixtures::class,
            RestaurantFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        $tagRestaurant = new TagRestaurant;
        $tagRestaurant
            ->setTag($this->tagRepository->findOneBy(['name' => 'Italien']))
            ->setRestaurant($this->restaurantRepository->findOneBy(['name' => 'Schwartz Deli']));

        $manager->flush();
    }
}

