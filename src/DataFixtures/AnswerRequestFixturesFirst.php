<?php

namespace App\DataFixtures;

use App\Entity\AnswerRequest;
use App\Entity\ResearchRequest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AnswerRequestFixturesFirst extends Fixture implements DependentFixtureInterface
{
    private const ANSWER_REQUEST_FIRST_PART = [
        [
            'name' => 'section',
            'question' => 'Requestor',
            'answer' => 'Requestor'
        ],
        [
            'name' => 'open-question',
            'question' => 'Project name',
            'answer' => 'Wild Project'
        ],
        [
            'name' => 'section',
            'question' => 'Research objectives',
            'answer' => 'Research objectives'
        ],
        [
            'name' => 'open-question',
            'question' => 'Research goal',
            'answer' => 'the goal of Lean is to deliver the maximum customer
                            value in the shortest sustainable lead time while
                            providing the highest possible quality to customers and society.'
        ],
        [
            'name' => 'open-question',
            'question' => 'Available data',
            'answer' => 'Cover almost all user-related topics, gather informations on
                            users feelings, motivations and daily routines, or how they use products.'
        ],
        [
            'name' => 'open-question',
            'question' => 'Research questions',
            'answer' => 'Cover almost all user-related topics, gather informations on
                            users feelings, motivations and daily routines, or how they use products.'
        ],
        [
            'name' => 'separator',
            'question' => 'separator',
            'answer' => 'separator'
        ],
        [
            'name' => 'section',
            'question' => 'Notes & additional comments',
            'answer' => 'Notes & additional comments'
        ],
        [
            'name' => 'date-picker',
            'question' => 'What is the deadline of your research?',
            'answer' => '2022-07-15'
        ],
        [
            'name' => 'open-question',
            'question' => 'Note from requestor',
            'answer' => 'No answer'
        ]

    ];

    public function load(ObjectManager $manager): void
    {
        for ($count = 4; $count <= 6; $count++) {
            foreach (self::ANSWER_REQUEST_FIRST_PART as $answerRequestValue) {
                $answerRequest = new AnswerRequest();
                $answerRequest
                    ->setName($answerRequestValue['name'])
                    ->setQuestion($answerRequestValue['question'])
                    ->setAnswer($answerRequestValue['answer']);
                if ($this->getReference('research_request_' . $count) instanceof ResearchRequest) {
                    $answerRequest->setResearchRequest($this->getReference('research_request_' . $count));
                }

                $manager->persist($answerRequest);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {

        return [

            ResearchRequestFixtures::class,

        ];
    }
}
