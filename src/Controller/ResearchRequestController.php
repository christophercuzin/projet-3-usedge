<?php

namespace App\Controller;

use App\Entity\ResearchRequest;
use App\Repository\ResearchRequestRepository;
use App\Repository\TemplateComponentRepository;
use App\Service\CheckDataUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResearchRequestController extends AbstractController
{
    #[Route('/research-request/add/{id}', name: 'research_request_add', methods: ['GET', 'POST'])]
    public function index(
        int $id,
        TemplateComponentRepository $tempCompRepository,
        CheckDataUtils $checkDataUtils,
        Request $request
    ): Response {
        $dataComponent = $checkDataUtils->trimData($request);
        $project = $dataComponent['project'];
        $templateId = $dataComponent['template_id'];
        $requestComponents = $tempCompRepository->findBy(['researchTemplate' => $id], ['numberOrder' => 'ASC']);

        return $this->render('research_request/add.html.twig', [
            'requestComponents' => $requestComponents,
            'project' => $project,
            'templateId' => $templateId,
        ]);
    }

    #[Route('/research-request/edit/{id}', name: 'research_request_edit')]
    public function edit(
        ResearchRequest $researchRequest,
        ResearchRequestRepository $researchRequestRepo,
        CheckDataUtils $checkDataUtils,
        Request $request
    ): Response {

        return $this->render('research_request/edit.html.twig', [
            'researchRequest' => $researchRequest,
        ]);
    }
}
