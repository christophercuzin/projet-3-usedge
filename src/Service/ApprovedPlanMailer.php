<?php

namespace App\Service;

use App\Repository\ResearchPlanRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;

class ApprovedPlanMailer
{
    private MailerInterface $mailer;
    private ParameterBagInterface $parameters;
    private ResearchPlanRepository $resPlanRepository;
    private string $templateName;


    public function __construct(
        MailerInterface $mailer,
        ParameterBagInterface $parameters,
        ResearchPlanRepository $resPlanRepository,
    ) {
        $this->mailer = $mailer;
        $this->parameters = $parameters;
        $this->resPlanRepository = $resPlanRepository;
    }

    public function getTemplateName(array $dataComponent): void
    {
        $researchPlan = $this->resPlanRepository->findOneBy(['id' => $dataComponent['research-plan-id']]);
        if (
            $researchPlan != null &&
            $researchPlan->getResearchRequest()->getResearchTemplate()->getName() != null
        ) {
            $this->templateName = $researchPlan->getResearchRequest()->getResearchTemplate()->getName();
        }
    }

    public function approvedPlanSendMail(): void
    {
        $email = new TemplatedEmail();
        if (is_string($this->parameters->get('mailer_from'))) {
            $email->from($this->parameters->get('mailer_from'));
        }
        if (is_string($this->parameters->get('mailer_to'))) {
            $email->to($this->parameters->get('mailer_to'));
        }
        $email->subject('Your research plan has been approved');
        $email->htmlTemplate('mail-template/approvedPlanTemplateMail.html.twig');
        $email->context(['templateName' => $this->templateName]);
        $this->mailer->send($email);
    }
}
