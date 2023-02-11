<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ADMIN_ONE_REFERENCE = 'admin-one';
    public const ADMIN_TWO_REFERENCE = 'admin-two';
    public const CUSTOMER_ONE_REFERENCE = 'customer-one';

    public function __construct(private UserRepository $userRepository, private UserPasswordHasherInterface $passwordHasher)
    {}

    public function load(ObjectManager $manager): void
    {
        $adminOne = new User;
        $adminOne
            ->setEmail('louispelarrey@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($adminOne, 'string'))
            ->setRoles(['ROLE_ADMIN'])
            ->setFirstname('Louis')
            ->setLastname('Pelarrey')
            ->setAddress('1 rue de la petite maison')
            ->setNumberPhone('0649088205')
        ;

        $manager->persist($adminOne);

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

        $adminTwo = new User;
        $adminTwo
            ->setEmail('krmdahoumane@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($adminTwo, 'karimtest'))
            ->setRoles(['ROLE_ADMIN'])
            ->setFirstname('Karim')
            ->setLastname('Dahoumane')
            ->setAddress('1 avenue de la grande maison')
            ->setNumberPhone('0777777777')
        ;

        $manager->persist($adminTwo);

        $customerOne = new User;
        $customerOne
            ->setEmail('customer1@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($customerOne, 'customer1'))
            ->setRoles(['ROLE_USER'])
            ->setFirstname('Customer')
            ->setLastname('One')
            ->setAddress('1 rue de la petite maison')
            ->setNumberPhone('000000001')
        ;

        $manager->persist($customerOne);

        $this->addReference(self::ADMIN_ONE_REFERENCE, $adminOne);
        $this->addReference(self::ADMIN_TWO_REFERENCE, $adminTwo);
        $this->addReference(self::CUSTOMER_ONE_REFERENCE, $customerOne);

        $customerTwo = new User;
        $customerTwo
            ->setEmail('customer2@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($customerTwo, 'customer2'))
            ->setRoles(['ROLE_USER'])
            ->setFirstname('Customer')
            ->setLastname('Two')
            ->setAddress('1 rue de la petite maison')
            ->setNumberPhone('000000002')
        ;

        $manager->persist($customerTwo);

        $delivererOne = new User;
        $delivererOne
            ->setEmail('deliverer1@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($delivererOne, 'deliverer1'))
            ->setRoles(['ROLE_DELIVERER'])
            ->setFirstname('JeanMi')
            ->setLastname('Du13')
            ->setAddress('1 rue de la petite maison')
            ->setNumberPhone('000000001')
        ;

        $manager->persist($delivererOne);

        $restaurantOne = new User;
        $restaurantOne
            ->setEmail('restaurant1@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($restaurantOne, 'restaurant1'))
            ->setRoles(['ROLE_RESTAURANT'])
            ->setFirstname('Kaaris')
            ->setLastname('Le mélangeur')
            ->setAddress('1 rue de la petite maison')
            ->setNumberPhone('000000001')
        ;

        $manager->persist($restaurantOne);

        $manager->flush();
    }
}

