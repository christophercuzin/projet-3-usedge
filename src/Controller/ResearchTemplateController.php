<?php

namespace App\Controller;

use App\Entity\Component;
use App\Entity\ResearchTemplate;
use App\Form\ResearchTemplateType;
use App\Repository\ComponentRepository;
use App\Repository\ResearchTemplateRepository;
use App\Repository\TemplateComponentRepository;
use App\Service\CheckDataUtils;
use App\Service\ComponentManager;
use App\Service\ComponentUpdateManager;
use App\Service\ComponentUtils;
use App\Service\ResearchTemplateDetailsUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use App\Service\RetrieveAnswers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/research-template', name: 'research_template_')]
class ResearchTemplateController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        ResearchTemplateRepository $templateRepository,
        TemplateComponentRepository $tempCompRepository,
        CheckDataUtils $checkDataUtils,
        RetrieveAnswers $retrieveAnswers
    ): Response {
        $dataComponent =  $checkDataUtils->trimData($request);

        if (
            isset($dataComponent['research-template-id']) &&
            $this->isCsrfTokenValid(
                'add' . $dataComponent['research-template-id'],
                $dataComponent['_token_template' . $dataComponent['research-template-id']]
            )
        ) {
            if (isset($dataComponent['research-template-status'])) {
                $templateRepository->updateTemplateStatus($dataComponent);
            }

            if (isset($dataComponent['components-number-count'])) {
                $orderNumber = $retrieveAnswers->retrieveOrderComponents($dataComponent);
                $tempCompRepository->updateNumberOrder($orderNumber);
            }
        }

        $researchTemplate = new ResearchTemplate();
        $form = $this->createForm(ResearchTemplateType::class, $researchTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (
                $this->isCsrfTokenValid(
                    'add_research_template',
                    $dataComponent['_token_add_research_template']
                )
            ) {
                $templateRepository->add($researchTemplate, true);
                $id = $researchTemplate->getId();
                return $this->redirectToRoute('research_template_add', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
        }
        $researchTemplateList = $templateRepository->findByStatus();
        $archiveTemplateList = $templateRepository->findByArchiveStatus();

        return $this->renderForm('research_template/index.html.twig', [
            'form' => $form,
            'researchTemplates' => $researchTemplateList,
            'archiveResearchTemplates' => $archiveTemplateList,
        ]);
    }

    #[Route('/archived', name: 'archive', methods: ['GET', 'POST'])]
    public function archive(
        Request $request,
        ResearchTemplateRepository $templateRepository,
        CheckDataUtils $checkDataUtils,
    ): Response {
        $dataComponent =  $checkDataUtils->trimData($request);

        $researchTemplate = new ResearchTemplate();
        $form = $this->createForm(ResearchTemplateType::class, $researchTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (
                $this->isCsrfTokenValid(
                    'add_research_template',
                    $dataComponent['_token_add_research_template']
                )
            ) {
                $templateRepository->add($researchTemplate, true);
                $id = $researchTemplate->getId();
                return $this->redirectToRoute('research_template_add', ['id' => $id], Response::HTTP_SEE_OTHER);
            }
        }
        $archiveTemplateList = $templateRepository->findByArchiveStatus();

        return $this->renderForm('research_template/archive.html.twig', [
            'form' => $form,
            'archiveResearchTemplates' => $archiveTemplateList,
        ]);
    }

    #[Route('/archive/{id}', name:'app_archive')]
    public function archiveTemplate(
        ResearchTemplateRepository $researchRepository,
        ResearchTemplate $researchTemplate
    ): Response {
        $researchTemplate->setStatus('archive');

        $researchRepository->add($researchTemplate, true);

        return $this->redirectToRoute('research_template_index');
    }

    #[Route('/add/{id}', name: 'add', methods: ['GET', 'POST'])]
    public function add(
        Request $request,
        ResearchTemplate $researchTemplate,
        ComponentUtils $componentUtils,
        CheckDataUtils $checkDataUtils,
        ComponentManager $componentManager
    ): Response {

        $dataComponent = $checkDataUtils->trimData($request);
        if (
            !empty($dataComponent) &&
            $this->isCsrfTokenValid(
                'add_component',
                $dataComponent['_token_add_component']
            )
        ) {
            $id = $componentManager->initComponent($dataComponent, $researchTemplate);
            return $this->redirectToRoute('research_template_add', [
                'id' => $id,
            ], Response::HTTP_SEE_OTHER);
        }

        $validationErrors = $componentUtils->getCheckErrors();

        return $this->render('research_template/add.html.twig', [
            'researchTemplate' => $researchTemplate,
            'errors' => $validationErrors
        ]);
    }

    #[Route('/edit/{researchTemplateId}/{componentId}', name: 'edit_component')]
    #[Entity('researchTemplate', options: ['id' => 'researchTemplateId'])]
    #[Entity('component', options: ['id' => 'componentId'])]
    public function edit(
        Request $request,
        Component $component,
        ResearchTemplate $researchTemplate,
        CheckDataUtils $checkDataUtils,
        ComponentUpdateManager $compUpdateManager,
        ComponentUtils $componentUtils,
    ): Response {

        $dataComponent = $checkDataUtils->trimData($request);
        $componentId = $component->getId();
        $researchTemplateId = $researchTemplate->getId();

        if (
            !empty($dataComponent) &&
            $this->isCsrfTokenValid(
                'edit_component',
                $dataComponent['_token_edit_component']
            )
        ) {
            $id = $compUpdateManager->updateComponent($dataComponent, $researchTemplate, $componentId);
            return $this->redirectToRoute('research_template_add', [
                'id' => $id,
            ], Response::HTTP_SEE_OTHER);
        }

        $validationErrors = $componentUtils->getCheckErrors();

        return $this->render('research_template/edit.html.twig', [
            'researchTemplate' => $researchTemplate,
            'componentId' => $componentId,
            'researchTemplateId' => $researchTemplateId,
            'errors' => $validationErrors,
        ]);
    }

    #[Route('/{researchTemplateId}/{componentId}', name: 'component_delete', methods: ['POST'])]
    #[Entity('researchTemplate', options: ['id' => 'researchTemplateId'])]
    #[Entity('component', options: ['id' => 'componentId'])]
    public function delete(
        Request $request,
        Component $component,
        ComponentRepository $componentRepository,
        ResearchTemplate $researchTemplate,
    ): Response {

        if (is_string($request->request->get('_token' . $component->getId()))) {
            if (
                $this->isCsrfTokenValid('delete' . $component->getId(), $request
                ->request->get('_token' . $component->getId()))
            ) {
                $componentRepository->remove($component, true);
            }
        }
        $id = $researchTemplate->getId();

        return $this->redirectToRoute('research_template_add', ['id' => $id], Response::HTTP_SEE_OTHER);
    }

    #[Route('/details/edit/{id}', name: 'edit_details', methods: ['GET', 'POST'])]
    public function editDetails(
        Request $request,
        ResearchTemplate $researchTemplate,
        ResearchTemplateDetailsUtils $resTempDetailsUtils,
        CheckDataUtils $checkDataUtils,
    ): Response {

        $dataComponent = $checkDataUtils->trimData($request);
        $resTempDetailsUtils->checkResearchTemplateData($dataComponent);
        $validationErrors = $resTempDetailsUtils->getCheckErrors();
        if (
            empty($validationErrors) &&
            $this->isCsrfTokenValid(
                'edit_research_template',
                $dataComponent['_token_edit_research_template']
            )
        ) {
            $resTempDetailsUtils->updateResearchTemplateDetails($dataComponent, $researchTemplate);
        }

        return $this->redirectToRoute('research_template_index', [
        ]);
    }
}
