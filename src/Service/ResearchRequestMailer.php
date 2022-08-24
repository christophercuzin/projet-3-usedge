<?php

namespace App\Service;

use App\Repository\ResearchTemplateRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;

class ResearchRequestMailer
{
    private MailerInterface $mailer;
    private ParameterBagInterface $parameters;
    private ResearchTemplateRepository $resTempRepository;
    private string $templateName;


    public function __construct(
        MailerInterface $mailer,
        ParameterBagInterface $parameters,
        ResearchTemplateRepository $resTempRepository,
    ) {
        $this->mailer = $mailer;
        $this->parameters = $parameters;
        $this->resTempRepository = $resTempRepository;
    }

    public function getTemplateName(array $dataComponent): void
    {
        $researchTemplate = $this->resTempRepository->findOneBy([
            'id' => $dataComponent['research_request_template_id']
        ]);
        if ($researchTemplate != null) {
            $this->templateName = $researchTemplate->getName();
        }
    }

    public function researchRequestSendMail(): void
    {
        $email = new TemplatedEmail();
        if (is_string($this->parameters->get('mailer_from'))) {
            $email->from($this->parameters->get('mailer_from'));
        }
        if (is_string($this->parameters->get('mailer_to'))) {
            $email->to($this->parameters->get('mailer_to'));
        }
        $email->subject('A new research request has been submitted');
        $email->htmlTemplate('mail-template/researchRequestTemplateMail.html.twig');
        $email->context(['templateName' => $this->templateName,]);
        $this->mailer->send($email);
    }
}
