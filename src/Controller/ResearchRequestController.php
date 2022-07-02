<?php

namespace App\Controller;

use App\Entity\ResearchTemplate;
use App\Repository\TemplateComponentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResearchRequestController extends AbstractController
{
    #[Route('/research-request/add', name: 'research_request_add')]
    public function index(TemplateComponentRepository $tempCompRepository): Response
    {
        //$researchRequest = $tempCompRepository->findBy(['researchTemplate' => '28'], ['numberOrder' => 'ASC']);

        return $this->render('research_request/add.html.twig', [
            'controller_name' => 'ResearchRequestController',
        ]);
    }
}
