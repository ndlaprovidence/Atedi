<?php

namespace App\Entity;

use App\Entity\Software;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\InterventionReport;
use App\Repository\SoftwareInterventionReportRepository;

#[ORM\Entity(repositoryClass: SoftwareInterventionReportRepository::class)]
#[ORM\Table(name: "tbl_software_intervention_report")]
class SoftwareInterventionReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $action;

    #[ORM\ManyToOne(targetEntity: Software::class, inversedBy: "softwareInterventionReports")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Software $software;

    #[ORM\ManyToOne(targetEntity: InterventionReport::class, inversedBy: "softwareInterventionReports")]
    #[ORM\JoinColumn(nullable: false)]
    private ?InterventionReport $intervention_report;

    public function __toString()
    {
        return $this->getSoftware()->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getSoftware(): ?Software
    {
        return $this->software;
    }

    public function setSoftware(?Software $software): self
    {
        $this->software = $software;

        return $this;
    }

    public function getInterventionReport(): ?InterventionReport
    {
        return $this->intervention_report;
    }

    public function setInterventionReport(?InterventionReport $intervention_report): self
    {
        $this->intervention_report = $intervention_report;

        return $this;
    }
}
