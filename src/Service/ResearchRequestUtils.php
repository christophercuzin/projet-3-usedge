<?php

namespace App\Service;

use App\Entity\AnswerRequest;
use App\Entity\ResearchRequest;
use App\Entity\ResearchTemplate;
use App\Repository\ResearchRequestRepository;
use App\Repository\ResearchTemplateRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class ResearchRequestUtils
{
    private EntityManagerInterface $entityManager;
    private ResearchTemplateRepository $resTempRepository;
    private ResearchRequestRepository $resReqRepository;
    private array $checkErrors = [];

    public function __construct(
        EntityManagerInterface $entityManager,
        ResearchTemplateRepository $resTempRepository,
        ResearchRequestRepository $resReqRepository,
    ) {
        $this->entityManager = $entityManager;
        $this->resTempRepository = $resTempRepository;
        $this->resReqRepository = $resReqRepository;
    }

    public function getCheckErrors(): array
    {
        return $this->checkErrors;
    }

    public function researchRequestSortAnswer(array $dataComponent): array
    {
        $componentIdList = [];
        foreach ($dataComponent as $specification => $data) {
            if (str_contains($specification, 'request-component-id')) {
                $componentIdList[] = $data;
            }
        }
        $answerList = [];
        $componentNumber = 1;
        foreach ($componentIdList as $componentId) {
            if ($dataComponent['request-component-name-' . $componentId] === 'multiple-choice' . $componentNumber) {
                $multipleAnswers = [];
                $multipleChoiceCount = $dataComponent['counter-answer-' . $componentId];
                for ($i = 0; $i < $multipleChoiceCount; $i++) {
                    $multipleAnswers[] = $dataComponent['multiple-answer-' . $componentId . '-' . $i];
                }
                $answerList[] = [
                    'request-component-name' => $dataComponent['request-component-name-' . $componentId],
                    'question' => $dataComponent['request-component-question-' . $componentId],
                    'multipleAnswer' => $multipleAnswers,
                ];
                continue;
            }
            if (!empty($dataComponent['answer-' . $componentId])) {
                $answerList[] = [
                    'request-component-name' => $dataComponent['request-component-name-' . $componentId],
                    'answer' => $dataComponent['answer-' . $componentId],
                    'question' => $dataComponent['request-component-question-' . $componentId]
                ];
            } else {
                $answerList[] = [
                    'request-component-name' => $dataComponent['request-component-name-' . $componentId],
                    'answer' => 'No answer',
                    'question' => $dataComponent['request-component-question-' . $componentId]
                ];
            }
            $componentNumber++;
        }

        return $answerList;
    }

    public function researchRequestCheckDate(array $answerList): void
    {
        foreach ($answerList as $answer) {
            if ($answer['request-component-name'] === 'date-picker') {
                if (
                    !empty($answer['answer']) &&
                    $answer['answer'] !== 'No answer' &&
                    !preg_match("/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/", $answer['answer'])
                ) {
                    $this->checkErrors[] = "This format of date is not available.";
                }
            }
        }
    }

    public function researchRequestCheckURL(array $answerList): void
    {
        foreach ($answerList as $answer) {
            if ($answer['request-component-name'] === 'external-link') {
                if (
                    !empty($answer['answer']) &&
                    $answer['answer'] !== 'No answer' &&
                    !filter_var($answer['answer'], FILTER_VALIDATE_URL)
                ) {
                    $this->checkErrors[] = "The URL is not valid.";
                }
            }
        }
    }

    public function addResearchRequest(array $dataComponent): void
    {
        $researchTemplate = $this->resTempRepository->findOneBy(['id' => $dataComponent['template_id']]);
        $entityManager = $this->entityManager;
        $researchRequest = new ResearchRequest();
        $creationDate = new DateTime("now");

        if ($researchTemplate instanceof ResearchTemplate) {
            $researchRequest->setResearchTemplate($researchTemplate);
        }
        $researchRequest->setCreationDate($creationDate);
        $researchRequest->setStatus($dataComponent['research-request-status']);
        $researchRequest->setProject($dataComponent['project']);
        $researchRequest->setOwner($dataComponent['owner']);
        $entityManager->persist($researchRequest);

        $entityManager->flush();
    }

    public function addResearchRequestAnswer(array $answerList): void
    {
        $researchRequest = $this->resReqRepository->findOneBy([], ['id' => 'DESC']);
        $entityManager = $this->entityManager;
        $count = 1;
        foreach ($answerList as $answers) {
            $requestAnswers = new AnswerRequest();

            if ($researchRequest instanceof ResearchRequest) {
                $requestAnswers->setResearchRequest($researchRequest);
            }
            $requestAnswers->setName($answers['request-component-name']);
            if ($answers['request-component-name'] != 'multiple-choice' . $count) {
                $requestAnswers->setAnswer($answers['answer']);
            } else {
                $requestAnswers->setMultipleAnswers($answers['multipleAnswer']);
                $requestAnswers->setAnswer('No answer');
            }
            $requestAnswers->setQuestion($answers['question']);

            $entityManager->persist($requestAnswers);
            $count++;
        }

        $entityManager->flush();
    }

    public function updateResearchRequestStatus(array $dataComponent): void
    {
        $entityManager = $this->entityManager;
        if (isset($dataComponent['research-request-id']) && !empty($dataComponent['research-request-id'])) {
            $id = $dataComponent['research-request-id'];
            $researchRequest = $this->resReqRepository->findOneBy(['id' => $id]);
        } else {
            $researchRequest = $this->resReqRepository->findOneBy([], ['id' => 'DESC']);
        }

        if ($researchRequest != null) {
            $researchRequest->setStatus($dataComponent['research-request-status']);
        }
        $entityManager->flush();
    }
}
