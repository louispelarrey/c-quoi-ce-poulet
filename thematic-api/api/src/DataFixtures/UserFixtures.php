<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        )
    {}

    public function load(ObjectManager $manager): void
    {
        $user = new User;
        $user
            ->setEmail('louispelarrey@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($user, 'string'))
            ->setRoles(['ROLE_ADMIN'])
            ->setFirstname('Louis')
            ->setLastname('Pelarrey')
            ->setAddress('1 rue de la petite maison')
            ->setNumberPhone('0649088205')
        ;

        $manager->persist($user);

        $user = new User;
        $user
            ->setEmail('user@user.com')
            ->setPassword($this->passwordHasher->hashPassword($user, 'string'))
            ->setRoles(['ROLE_USER'])
            ->setFirstname('User')
            ->setLastname('User')
            ->setAddress('1 rue de la petite maison')
            ->setNumberPhone('0649088205')
        ;
        $manager->persist($user);
        $manager->flush();
    }
}
