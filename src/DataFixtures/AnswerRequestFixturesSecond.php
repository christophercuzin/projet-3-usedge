<?php

namespace App\DataFixtures;

use App\Entity\AnswerRequest;
use App\Entity\ResearchRequest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AnswerRequestFixturesSecond extends Fixture implements DependentFixtureInterface
{
    private const ANSWER_REQUEST_SECOND_PART = [
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
            'name' => 'open-question',
            'question' => 'Purpose statement',
            'answer' => 'Cover almost all user-related topics, gather informations on
                            users feelings, motivations and daily routines, or how they use products.'
        ],
        [
            'name' => 'section',
            'question' => 'Research participants',
            'answer' => 'Research participants'
        ],
        [
            'name' => 'open-question',
            'question' => 'Number of participants',
            'answer' => '10'
        ],
        [
            'name' => 'separator',
            'question' => 'separator',
            'answer' => 'separator'
        ],
        [
            'name' => 'section',
            'question' => 'Research questions',
            'answer' => 'Research questions'
        ],
        [
            'name' => 'evaluation-scale',
            'question' => 'From 1 to 5, what is the progress of your idea?',
            'answer' => '4'
        ],
        [
            'name' => 'multiple-choice',
            'question' => 'What IT category does your project concern?',
            'answer' => ['Analytics', 'Infrastructure']
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        for ($count = 1; $count <= 3; $count++) {
            foreach (self::ANSWER_REQUEST_SECOND_PART as $answerRequestValue) {
                $answerRequest = new AnswerRequest();
                if ($answerRequestValue['name'] != 'multiple-choice' && is_string($answerRequestValue['answer'])) {
                    $answerRequest
                        ->setName($answerRequestValue['name'])
                        ->setQuestion($answerRequestValue['question'])
                        ->setAnswer($answerRequestValue['answer'])
                        ;
                } elseif (is_array($answerRequestValue['answer'])) {
                    $answerRequest
                        ->setName($answerRequestValue['name'])
                        ->setQuestion($answerRequestValue['question'])
                        ->setAnswer('No answer')
                        ->setMultipleAnswers($answerRequestValue['answer'])
                        ;
                }

                if ($this->getReference('research_request_' . $count) instanceof ResearchRequest) {
                    $answerRequest->setResearchRequest($this->getReference('research_request_' . $count));
                }

                $manager->persist($answerRequest);
            }
            $manager->flush();
        }
    }

    public function getDependencies()
    {

        return [

            ResearchRequestFixtures::class,

        ];
    }
}
