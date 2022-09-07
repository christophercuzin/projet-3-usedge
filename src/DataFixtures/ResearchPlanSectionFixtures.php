<?php

namespace App\DataFixtures;

use App\Entity\ResearchPlan;
use App\Entity\ResearchPlanSection;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ResearchPlanSectionFixtures extends Fixture implements DependentFixtureInterface
{
    private const RESEARCH_PLAN_SECTION = [
        [
            'research_plan' => 'research_plan_1',

            'title' => 'Wild Project Workshop',

            'workshopName' => 'Explorative interview',

            'workshopDescription' => 'Cover almost all user-related topics, gather
            informations on users’ feelings, motivations
            and daily routines, or how they use products.',

            'recommendation' => 'If you have little time, you can send the link to the
            "board" by asking participants to start the exercise
            (by indicating their initials on the post-its for example).
            Suggest that they tune in 15-30 minutes before the workshop
            if they have any questions, so as not to take away from the workshop time.',

            'objectives' => ['First, define the problems and expectations.',
            'Second, hare your innovations and interact with attendees.',
            'Last, Collect information, gather it and analyze it.'],
        ],

        [
            'research_plan' => 'research_plan_2',

            'title' => 'Wild Project Workshop',

            'workshopName' => 'Explorative interview',

            'workshopDescription' => 'Cover almost all user-related topics, gather
            informations on users’ feelings, motivations
            and daily routines, or how they use products.',

            'recommendation' => 'If you have little time, you can send the link to the
            "board" by asking participants to start the exercise
            (by indicating their initials on the post-its for example).
            Suggest that they tune in 15-30 minutes before the workshop
            if they have any questions, so as not to take away from the workshop time.',

            'objectives' => ['First, define the problems and expectations.',
            'Second, hare your innovations and interact with attendees.',
            'Last, Collect information, gather it and analyze it.'],
        ],

        [
            'research_plan' => 'research_plan_3',

            'title' => 'Wild Project Workshop',

            'workshopName' => 'Explorative interview',

            'workshopDescription' => 'Cover almost all user-related topics, gather
            informations on users’ feelings, motivations
            and daily routines, or how they use products.',

            'recommendation' => 'If you have little time, you can send the link to the
            "board" by asking participants to start the exercise
            (by indicating their initials on the post-its for example).
            Suggest that they tune in 15-30 minutes before the workshop
            if they have any questions, so as not to take away from the workshop time.',

            'objectives' => ['First, define the problems and expectations.',
            'Second, hare your innovations and interact with attendees.',
            'Last, Collect information, gather it and analyze it.'],
        ],

        [
            'research_plan' => 'research_plan_4',

            'title' => 'Wild Project Workshop',

            'workshopName' => 'Explorative interview',

            'workshopDescription' => 'Cover almost all user-related topics, gather
            informations on users’ feelings, motivations
            and daily routines, or how they use products.',

            'recommendation' => 'If you have little time, you can send the link to the
            "board" by asking participants to start the exercise
            (by indicating their initials on the post-its for example).
            Suggest that they tune in 15-30 minutes before the workshop
            if they have any questions, so as not to take away from the workshop time.',

            'objectives' => ['First, define the problems and expectations.',
            'Second, hare your innovations and interact with attendees.',
            'Last, Collect information, gather it and analyze it.'],
        ]
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::RESEARCH_PLAN_SECTION as $planSectionValue) {
            $researchPlanSection = new ResearchPlanSection();
            $researchPlanSection
                ->setTitle($planSectionValue['title'])
                ->setWorkshopName($planSectionValue['workshopName'])
                ->setWorkshopDescription($planSectionValue['workshopDescription'])
                ->setRecommendation($planSectionValue['recommendation'])
                ->setObjectives($planSectionValue['objectives'])
                ;

            if ($this->getReference($planSectionValue['research_plan']) instanceof ResearchPlan) {
                $researchPlanSection->setResearchPlan($this->getReference($planSectionValue['research_plan']));
            }

            $manager->persist($researchPlanSection);
        }

        $manager->flush();
    }

    public function getDependencies()
    {

        return [

          ResearchPlanFixtures::class,

        ];
    }
}
