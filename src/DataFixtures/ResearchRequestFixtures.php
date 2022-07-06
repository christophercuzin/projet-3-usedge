<?php

namespace App\DataFixtures;

use App\Entity\ResearchRequest;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ResearchRequestFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $status = ['Waiting list', 'Dropped', 'Solved', 'Under review', 'Draft'];

        $requestNumber = 1;
        for ($t = 1; $t <  16; $t++) {
            for ($r = 0; $r <  3; $r++) {
                $date = new DateTime();
                $researchRequest = new ResearchRequest();
                $researchRequest->setResearchTemplate($this->getReference('researchTemplate_' . $t));
                $researchRequest->setCreationDate($date);
                $researchRequest->setStatus($status[array_rand($status)]);
                $researchRequest->setProject('Webb app');
                $researchRequest->setOwner($faker->name());
                $this->addReference('researchRequest_' . $requestNumber, $researchRequest);
                $requestNumber++;
                $manager->persist($researchRequest);
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {

        return [

          ResearchTemplateFixtures::class,

        ];
    }
}
