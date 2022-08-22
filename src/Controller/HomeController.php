<?php

namespace App\Controller;

use App\Entity\ResearchPlan;
use App\Repository\ResearchPlanRepository;
use App\Repository\ResearchRequestRepository;
use App\Repository\ResearchTemplateRepository;
use App\Service\CheckDataUtils;
use App\Service\ResearchPlanUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/modal/{id}', name: 'app_home_get_modal', methods: ['GET'])]
    public function getModal(ResearchPlan $researchPlan): Response
    {
        return $this->render('home/modals/_header_modal_title_plan.html.twig', [
            'researchPlan' => $researchPlan,
        ]);
    }

    #[Route('/validate/{id}', name: 'app_home_get_modal_validate', methods: ['GET'])]
    public function getModalValidate(ResearchPlan $researchPlan): Response
    {
        return $this->render('home/modals/_header_modal_validate_plan.html.twig', [
            'researchPlan' => $researchPlan,
        ]);
    }

    #[Route('/plan/status/{id}', name: 'app_home_get_plan_status', methods: ['GET'])]
    public function getPlanStatus(ResearchPlan $researchPlan): Response
    {
        return $this->render('home/_research_plans_status.html.twig', [
            'researchPlan' => $researchPlan,
        ]);
    }

    #[Route('/', name: 'app_home_get', methods: ['GET'])]
    public function indexGet(
        ResearchTemplateRepository $researchTemplates,
        ResearchRequestRepository $researchRequestRepo,
        ResearchPlanRepository $researchPlanRepo,
    ): Response {
        $researchTemplateList = $researchTemplates->findBy(['status' => 'active']);
        $researchRequests = $researchRequestRepo->findBy([], ['id' => 'DESC']);
        $researchPlans = $researchPlanRepo->findBy([], ['id' => 'DESC']);


        return $this->render('home/index.html.twig', [
            'researchTemplates' => $researchTemplateList,
            'researchRequests' => $researchRequests,
            'researchPlans' => $researchPlans,
        ]);
    }

    #[Route('/', name: 'app_home', methods: ['POST'])]
    public function index(
        Request $request,
        ResearchPlanRepository $researchPlanRepo,
        CheckDataUtils $checkDataUtils,
        ResearchPlanUtils $researchPlanUtils,
    ): Response {
        $dataComponent = $checkDataUtils->trimData($request);
        if (!empty($dataComponent)) {
            $id = $dataComponent['research-plan-id'];
            $researchPlan = $researchPlanRepo->findOneBy(['id' => $id]);
            $researchPlanUtils->updateResearchPlanStatus($dataComponent, $researchPlan);
        }

        return new response();
    }
}
