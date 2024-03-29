<?php

namespace App\Entity;

use App\Repository\ResearchRequestRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResearchRequestRepository::class)]
class ResearchRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: ResearchTemplate::class, inversedBy: 'researchRequests')]
    private ResearchTemplate $researchTemplate;

    #[ORM\OneToMany(mappedBy: 'researchRequest', targetEntity: AnswerRequest::class)]
    private Collection $answerRequests;

    #[ORM\Column(type: 'datetime')]
    private DateTimeInterface $creationDate;

    #[ORM\Column(type: 'string', length: 255)]
    private string $status;

    #[ORM\Column(type: 'string', length: 255)]
    private string $project;

    #[ORM\Column(type: 'string', length: 255)]
    private string $owner;

    #[ORM\OneToOne(mappedBy: 'researchRequest', targetEntity: ResearchPlan::class, cascade: ['persist', 'remove'])]
    private ResearchPlan $researchPlan;

    public function __construct()
    {
        $this->answerRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResearchTemplate(): ?ResearchTemplate
    {
        return $this->researchTemplate;
    }

    public function setResearchTemplate(ResearchTemplate $researchTemplate): self
    {
        $this->researchTemplate = $researchTemplate;

        return $this;
    }

    /**
     * @return Collection<int, AnswerRequest>
     */
    public function getAnswerRequests(): Collection
    {
        return $this->answerRequests;
    }

    public function addAnswerRequest(AnswerRequest $answerRequest): self
    {
        if (!$this->answerRequests->contains($answerRequest)) {
            $this->answerRequests[] = $answerRequest;
            $answerRequest->setResearchRequest($this);
        }

        return $this;
    }

    public function removeAnswerRequest(AnswerRequest $answerRequest): self
    {
        $this->answerRequests->removeElement($answerRequest);

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getProject(): ?string
    {
        return $this->project;
    }

    public function setProject(string $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getOwner(): ?string
    {
        return $this->owner;
    }

    public function setOwner(string $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getResearchPlan(): ?ResearchPlan
    {
        return $this->researchPlan;
    }

    public function setResearchPlan(ResearchPlan $researchPlan): self
    {
        // set the owning side of the relation if necessary
        if ($researchPlan->getResearchRequest() !== $this) {
            $researchPlan->setResearchRequest($this);
        }

        $this->researchPlan = $researchPlan;

        return $this;
    }

    public function getAnswers(): array
    {
        $researchRequests = $this->getAnswerRequests();
        $answers = [];
        foreach ($researchRequests as $answerRequest) {
            $answers[] = [$answerRequest->getName() => $answerRequest->getAnswer(), $answerRequest->getId()];
        }
        return $answers;
    }
    public function getMultipleAnswers(): array
    {
        $researchRequests = $this->getAnswerRequests();
        $multipleanswers = [];
        foreach ($researchRequests as $answerRequest) {
            $multipleanswers[] = [
                $answerRequest->getName() => $answerRequest->getMultipleAnswers(),
                $answerRequest->getId()
            ];
        }
        return $multipleanswers;
    }
}
