<?php

namespace App\Service;

use App\Entity\ResearchTemplate;
use Doctrine\ORM\EntityManagerInterface;

class ResearchTemplateDetailsUtils
{
    private EntityManagerInterface $entityManager;
    private array $checkErrors = [];

    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        $this->entityManager = $entityManager;
    }

    public function getCheckErrors(): array
    {
        return $this->checkErrors;
    }

    public function checkResearchTemplateData(array $dataComponent): void
    {
        if (
            empty($dataComponent['edit_research_template_icon_' . $dataComponent['template_id']]) ||
            empty($dataComponent['research_template_name']) ||
            empty($dataComponent['research_template_description']) ||
            empty($dataComponent['research_template_coach'])
        ) {
            $this->checkErrors[] = 'All fields are mandatory.';
        }

        if (
            strlen($dataComponent['edit_research_template_icon_' . $dataComponent['template_id']]) > 255 ||
            strlen($dataComponent['research_template_name']) > 255 ||
            strlen($dataComponent['research_template_coach']) > 255
        ) {
            $this->checkErrors[] = 'Maximum length is 255 characters.';
        }
    }
    public function updateResearchTemplateDetails(array $dataComponent, ResearchTemplate $researchTemplate): void
    {
        $entityManager = $this->entityManager;

        if (!empty($dataComponent)) {
            $researchTemplate->setName($dataComponent['research_template_name']);
            $researchTemplate->setIcon($dataComponent['edit_research_template_icon_' . $dataComponent['template_id']]);
            $researchTemplate->setDescription($dataComponent['research_template_description']);
            $researchTemplate->setCoach($dataComponent['research_template_coach']);
            $entityManager->persist($researchTemplate);
        }
        $entityManager->flush();
    }
}
