<?php

namespace App\Service;

use App\Repository\ResearchRequestRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;

class ResearchPlanMailer
{
    private MailerInterface $mailer;
    private ParameterBagInterface $parameters;
    private ResearchRequestRepository $resReqRepository;
    private string $templateName;
    private string $coachName;

    public function __construct(
        MailerInterface $mailer,
        ParameterBagInterface $parameters,
        ResearchRequestRepository $resReqRepository,
    ) {
        $this->mailer = $mailer;
        $this->parameters = $parameters;
        $this->resReqRepository = $resReqRepository;
    }

    public function getResearchRequestData(array $dataComponent): void
    {
        $researchRequest = $this->resReqRepository->findOneBy(['id' => $dataComponent['research-request-id']]);
        $this->coachName = $researchRequest->getResearchTemplate()->getCoach();
        $this->templateName = $researchRequest->getResearchTemplate()->getName();
    }

    public function researchPlanSendMail(): void
    {
        $email = new TemplatedEmail();
        if (is_string($this->parameters->get('mailer_from'))) {
            $email->from($this->parameters->get('mailer_from'));
        }
        if (is_string($this->parameters->get('mailer_to'))) {
            $email->to($this->parameters->get('mailer_to'));
        }
        $email->subject('A new research plan is available');
        $email->htmlTemplate('mail-template/researchPlanTemplateMail.html.twig');
        $email->context([
            'templateName' => $this->templateName,
            'coachName' => $this->coachName,
        ]);
        $this->mailer->send($email);
    }
}
