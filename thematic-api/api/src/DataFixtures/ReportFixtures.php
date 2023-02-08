<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;

use Doctrine\Persistence\ObjectManager;
use App\Entity\Report;
use App\Repository\ReportRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ReportFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private ReportRepository $reportRepository)
    {}

    public function load(ObjectManager $manager): void
    {   
        $report = new Report();
        $report
            ->setReportedBy($this->getReference(UserFixtures::ADMIN_ONE_REFERENCE))
            ->setReportedUser($this->getReference(UserFixtures::CUSTOMER_ONE_REFERENCE))
            ->setReason('I have never received my order')
        ;

        $manager->persist($report);
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}

