<?php

namespace App\Controller;

use App\Entity\ResearchRequest;
use App\Entity\ResearchTemplate;
use App\Repository\AnswerRequestRepository;
use App\Repository\ResearchRequestRepository;
use App\Repository\TemplateComponentRepository;
use App\Service\CheckDataUtils;
use App\Service\ResearchRequestMailer;
use App\Service\ResearchRequestUtils;
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
        Request $request,
        ResearchRequestUtils $requestUtils,
        ResearchRequestMailer $mailer,
        ResearchRequestRepository $resReqRepository,
        ResearchTemplate $researchTemplate,
    ): Response {
        $dataComponent = $checkDataUtils->trimData($request);
        $requestComponents = $tempCompRepository->findBy(['researchTemplate' => $id], ['numberOrder' => 'ASC']);
        $requestErrors = [];

        if (
            isset($dataComponent['project']) &&
            isset($dataComponent['_token_add_research_request']) &&
            $this->isCsrfTokenValid(
                'add_research_request',
                $dataComponent['_token_add_research_request']
            )
        ) {
            $lastResReqId = $requestUtils->addResearchRequest($dataComponent);
            return $this->render('research_request/add.html.twig', [
                'requestComponents' => $requestComponents,
                'researchTemplate' => $researchTemplate,
                'templateId' => $id,
                'lastResearchRequestId' => $lastResReqId,
            ]);
        }

        if (
            isset($dataComponent['research_request_template_id']) &&
            $this->isCsrfTokenValid(
                'add_research_request_answer',
                $dataComponent['_token_add_research_request_answer']
            )
        ) {
            $requestUtils->updateResearchRequestStatus($dataComponent);
            $answerList = $requestUtils->researchRequestSortAnswer($dataComponent);
            $requestUtils->researchRequestCheckDate($answerList);
            $requestUtils->researchRequestCheckURL($answerList);
            $requestErrors = $requestUtils->getCheckErrors();
            if (empty($requestErrors)) {
                $requestStatus = '';
                if ($resReqRepository->findOneBy([], ['id' => 'DESC']) instanceof ResearchRequest) {
                    $requestStatus = $resReqRepository->findOneBy([], ['id' => 'DESC'])->getStatus();
                }
                $requestUtils->addResearchRequestAnswer($answerList);
                $mailer->getTemplateName($dataComponent);
                if ($requestStatus === 'Waiting list') {
                    $mailer->researchRequestSendMail();
                } else {
                    return $this->redirectToRoute('app_home');
                }
            }
            return $this->render('research_request/confirm.html.twig', [
                'errors' => $requestErrors,
                'templateId' => $id,
            ]);
        }

        return $this->render('research_request/add.html.twig', [
            'requestComponents' => $requestComponents,
            'researchTemplate' => $researchTemplate,
            'templateId' => $id,
        ]);
    }

    #[Route('/research-request/add/external/{id}', name: 'research_request_add_external', methods: ['GET', 'POST'])]
    public function indexExternalLink(
        int $id,
        TemplateComponentRepository $tempCompRepository,
        CheckDataUtils $checkDataUtils,
        Request $request,
        ResearchRequestUtils $requestUtils,
        ResearchRequestMailer $mailer,
        ResearchRequestRepository $resReqRepository,
        ResearchTemplate $researchTemplate,
    ): Response {
        $dataComponent = $checkDataUtils->trimData($request);
        $requestComponents = $tempCompRepository->findBy(['researchTemplate' => $id], ['numberOrder' => 'ASC']);
        $requestErrors = [];

        if (
            isset($dataComponent['research_request_template_id']) &&
            $this->isCsrfTokenValid(
                'add_research_request_answer',
                $dataComponent['_token_add_research_request_answer']
            )
        ) {
            if (isset($dataComponent['project'])) {
                $requestUtils->researchRequestCheckProject($dataComponent);
            }
            $answerList = $requestUtils->researchRequestSortAnswer($dataComponent);
            $requestUtils->researchRequestCheckDate($answerList);
            $requestUtils->researchRequestCheckURL($answerList);
            $requestErrors = $requestUtils->getCheckErrors();
            if (empty($requestErrors)) {
                $requestStatus = '';
                if (isset($dataComponent['project'])) {
                    $lastResReqId = $requestUtils->addResearchRequest($dataComponent);
                    $requestUtils->updateResearchRequestStatus($dataComponent, $lastResReqId);
                    if ($resReqRepository->findOneBy(['id' => $lastResReqId]) != null) {
                        $requestStatus = $resReqRepository->findOneBy(['id' => $lastResReqId])->getStatus();
                    }
                } elseif ($resReqRepository->findOneBy([], ['id' => 'DESC']) instanceof ResearchRequest) {
                        $requestStatus = $resReqRepository->findOneBy([], ['id' => 'DESC'])->getStatus();
                }
                $requestUtils->addResearchRequestAnswer($answerList);
                $mailer->getTemplateName($dataComponent);
                if ($requestStatus === 'Waiting list') {
                    $mailer->researchRequestSendMail();
                } else {
                    return $this->redirectToRoute('app_home');
                }
            }
            return $this->render('research_request/confirm.html.twig', [
                'errors' => $requestErrors,
                'templateId' => $id,
            ]);
        }

        return $this->render('research_request/add.html.twig', [
            'requestComponents' => $requestComponents,
            'researchTemplate' => $researchTemplate,
            'templateId' => $id,
        ]);
    }

    #[Route('/research-request/edit/{id}', name: 'research_request_edit')]
    public function edit(
        ResearchRequest $researchRequest,
        AnswerRequestRepository $answerReqRepo,
        ResearchRequestUtils $requestUtils,
        CheckDataUtils $checkDataUtils,
        TemplateComponentRepository $tempCompRepository,
        ResearchRequestMailer $mailer,
        Request $request
    ): Response {
        $dataComponent = $checkDataUtils->trimData($request);
        $researchTemplate = $researchRequest->getResearchTemplate();
        $id = "";
        if ($researchTemplate != null) {
            $id = $researchTemplate->getId();
        }
        $requestComponents = $tempCompRepository->findBy(['researchTemplate' => $id], ['numberOrder' => 'ASC']);
        $answerRequests = $answerReqRepo->findBy(['researchRequest' => $researchRequest]);
        if (
            !empty($dataComponent) &&
            $this->isCsrfTokenValid(
                'edit_research_request_answer',
                $dataComponent['_token_edit_research_request_answer']
            )
        ) {
            $requestUtils->updateResearchRequestStatus($dataComponent);
            $answerList = $requestUtils->researchRequestSortAnswer($dataComponent);
            $requestUtils->researchRequestCheckDate($answerList);
            $requestUtils->researchRequestCheckURL($answerList);
            $requestErrors = $requestUtils->getCheckErrors();
            if (empty($requestErrors)) {
                $requestStatus = '';
                $requestStatus = $researchRequest->getStatus();
                $requestUtils->updateResearchRequestAnswer($answerList, $researchRequest);
                $mailer->getTemplateName($dataComponent);
                if ($requestStatus === 'Waiting list') {
                    $mailer->researchRequestSendMail();
                } else {
                    return $this->redirectToRoute('app_home');
                }
            }
            return $this->render('research_request/confirm.html.twig', [
                'errors' => $requestErrors,
                'researchRequest' => $researchRequest,
            ]);
        }
        return $this->render('research_request/edit.html.twig', [
            'researchRequest' => $researchRequest,
            'answerRequests' => $answerRequests,
            'requestComponents' => $requestComponents
        ]);
    }
}
