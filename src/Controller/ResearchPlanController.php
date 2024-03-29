<?php

namespace App\Controller;

use App\Entity\ResearchPlanSection;
use App\Repository\CanvasWorkshopsRepository;
use App\Entity\ResearchRequest;
use App\Repository\ResearchPlanRepository;
use App\Service\CheckDataUtils;
use App\Service\ResearchPlanMailer;
use App\Service\ResearchPlanUtils;
use App\Service\ResearchRequestUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResearchPlanController extends AbstractController
{
    #[Route('/research-plan/{id}', name: 'app_research_plan', methods: ['GET', 'POST'])]
    public function index(
        int $id,
        ResearchRequest $researchRequest,
        CanvasWorkshopsRepository $workshopRepository,
        Request $request,
        CheckDataUtils $checkDataUtils,
        ResearchPlanUtils $researchPlanUtils,
        ResearchRequestUtils $researchReqUtils,
        ResearchPlanMailer $mailer,
        ResearchPlanRepository $researchPlanRepo,
    ): Response {

        $dataComponent = $checkDataUtils->trimData($request);
        $researchPlan = $researchPlanRepo->findOneBy(['researchRequest' => $id]);
        $researchPlanErrors = [];

        if (empty($dataComponent) && !empty($researchPlan)) {
            return $this->redirectToRoute('research_plan_new_section', ['id' => $id]);
        } elseif (empty($dataComponent)) {
            $workshops = $workshopRepository->findAll();

            return $this->render('research_plan/research_plan.html.twig', [
                'workshops' => $workshops,
                'researchRequest' => $researchRequest,
                'researchPlan' => $researchPlan,
            ]);
        }

        $researchPlanUtils->researchPlanCheckEmpty($dataComponent);
        $researchPlanUtils->researchPlanCheckLength($dataComponent);
        $researchPlanErrors = $researchPlanUtils->getCheckErrors();
        if (
            empty($researchPlanErrors) &&
            $this->isCsrfTokenValid(
                'research_plan',
                $dataComponent['_token_research_plan']
            )
        ) {
            $researchReqUtils->updateResearchRequestStatus($dataComponent);
            $mailer->getResearchRequestData($dataComponent);
            $researchPlanUtils->addResearchPlan($dataComponent);
            $mailer->researchPlanSendMail();
        }

        return $this->render('research_plan/confirm.html.twig', ['errors' => $researchPlanErrors]);
    }

    #[Route('/research-plan/{id}/section', name: 'research_plan_new_section', methods: ['GET', 'POST'])]
    public function newSection(
        int $id,
        ResearchRequest $researchRequest,
        CanvasWorkshopsRepository $workshopRepository,
        Request $request,
        CheckDataUtils $checkDataUtils,
        ResearchPlanRepository $researchPlanRepo,
        ResearchPlanUtils $researchPlanUtils,
    ): Response {
        $researchPlan = $researchPlanRepo->findOneBy(['researchRequest' => $id]);
        $dataComponent = $checkDataUtils->trimData($request);

        if (
            empty($researchPlan) &&
            empty(!$dataComponent) &&
            $this->isCsrfTokenValid(
                'research_plan',
                $dataComponent['_token_research_plan']
            )
        ) {
            $researchPlanUtils->addResearchPlan($dataComponent);
            return $this->redirectToRoute('research_plan_new_section', ['id' => $id]);
        }
        if (
            !empty($researchPlan) &&
            !empty($dataComponent) &&
            $this->isCsrfTokenValid(
                'research_plan',
                $dataComponent['_token_research_plan']
            )
        ) {
            $researchPlanUtils->researchPlanCheckEmpty($dataComponent);
            $researchPlanUtils->researchPlanCheckLength($dataComponent);
            $researchPlanErrors = $researchPlanUtils->getCheckErrors();
            if (empty($researchPlanErrors)) {
                $researchPlanUtils->addResearchPlanSection($dataComponent, $researchPlan);
            } else {
                return $this->render('research_plan/confirm.html.twig', ['errors' => $researchPlanErrors]);
            }
        }

        $workshops = $workshopRepository->findAll();

        return $this->render('research_plan/research_plan.html.twig', [
            'workshops' => $workshops,
            'researchRequest' => $researchRequest,
            'researchPlan' => $researchPlan,
        ]);
    }

    #[Route('/research-plan/{researchRequestId}/section/{sectionId}
        ', name: 'edit_research_plan_section', methods: ['GET', 'POST'])]
    #[Entity('researchRequest', options: ['id' => 'researchRequestId'])]
    #[Entity('researchPlanSection', options: ['id' => 'sectionId'])]
    public function editSection(
        ResearchPlanSection $researchPlanSection,
        ResearchRequest $researchRequest,
        CanvasWorkshopsRepository $workshopRepository,
        Request $request,
        CheckDataUtils $checkDataUtils,
        ResearchPlanUtils $researchPlanUtils,
        ResearchPlanRepository $researchPlanRepo,
    ): Response {
        $resRequestId = $researchRequest->getId();
        $researchPlan = $researchPlanRepo->findOneBy(['researchRequest' => $resRequestId]);
        $dataComponent = $checkDataUtils->trimData($request);

        if (
            !empty($researchPlan) &&
            $researchPlanSection instanceof ResearchPlanSection &&
            !empty($dataComponent)
        ) {
            $researchPlanUtils->researchPlanCheckEmpty($dataComponent);
            $researchPlanUtils->researchPlanCheckLength($dataComponent);
            $researchPlanErrors = $researchPlanUtils->getCheckErrors();
            if (
                empty($researchPlanErrors) &&
                $this->isCsrfTokenValid(
                    'research_plan',
                    $dataComponent['_token_research_plan']
                )
            ) {
                $researchPlanUtils->addResearchPlanSection($dataComponent, $researchPlan);
            }
        }

        $workshops = $workshopRepository->findAll();

        return $this->render('research_plan/research_plan_edit.html.twig', [
            'workshops' => $workshops,
            'researchRequest' => $researchRequest,
            'researchPlan' => $researchPlan,
            'researchPlanSection' => $researchPlanSection
        ]);
    }

    #[Route('/research-plan/{id}/validation', name: 'research_plan_validation', methods: ['GET', 'POST'])]
    public function researchPlanValidation(
        int $id,
        Request $request,
        CheckDataUtils $checkDataUtils,
        ResearchPlanUtils $researchPlanUtils,
        ResearchPlanMailer $mailer,
        ResearchPlanRepository $researchPlanRepo,
        ResearchRequestUtils $researchReqUtils,
    ): Response {

        $dataComponent = $checkDataUtils->trimData($request);
        $researchPlan = $researchPlanRepo->findOneBy(['researchRequest' => $id]);

        if (
            !empty($dataComponent['research-plan-title']) ||
            !empty($dataComponent['workshop_description']) ||
            !empty($dataComponent['research-plan-recommendation'])
        ) {
            if ($this->isCsrfTokenValid('research_plan', $dataComponent['_token_research_plan'])) {
                $researchReqUtils->updateResearchRequestStatus($dataComponent);
                $researchPlanUtils->updateResearchPlanStatus($dataComponent, $researchPlan);
                $researchPlanUtils->addResearchPlanSection($dataComponent, $researchPlan);
                $mailer->getResearchRequestData($dataComponent);
                $mailer->researchPlanSendMail();
            }
        }
        $researchReqUtils->updateResearchRequestStatus($dataComponent);
        $mailer->getResearchRequestData($dataComponent);
        $researchPlanUtils->updateResearchPlanStatus($dataComponent, $researchPlan);
        $mailer->researchPlanSendMail();
        return $this->render('research_plan/confirm.html.twig');
    }

    #[Route('/research-plan/{id}/save', name: 'research_plan_save', methods: ['GET', 'POST'])]
    public function saveAndContinue(
        int $id,
        Request $request,
        CheckDataUtils $checkDataUtils,
        ResearchPlanUtils $researchPlanUtils,
        ResearchPlanRepository $researchPlanRepo,
        ResearchRequestUtils $researchReqUtils,
    ): Response {

        $dataComponent = $checkDataUtils->trimData($request);
        $researchPlan = $researchPlanRepo->findOneBy(['researchRequest' => $id]);
        if (
            !empty($researchPlan) &&
            $this->isCsrfTokenValid('research_plan', $dataComponent['_token_research_plan'])
        ) {
            $researchReqUtils->updateResearchRequestStatus($dataComponent);
            $researchPlanUtils->updateResearchPlanStatus($dataComponent, $researchPlan);
            $researchPlanUtils->addResearchPlanSection($dataComponent, $researchPlan);
        } else {
            $researchPlanUtils->addResearchPlan($dataComponent);
        }

        return $this->redirectToRoute('app_home');
    }
}
