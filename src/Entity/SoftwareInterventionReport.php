<?php

namespace App\Entity;

use App\Repository\SoftwareInterventionReportRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SoftwareInterventionReportRepository::class)
 * @ORM\Table(name="tbl_software_intervention_report")
 */
class SoftwareInterventionReport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $action;

    /**
     * @ORM\ManyToOne(targetEntity=Software::class, inversedBy="softwareInterventionReports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $software;

    /**
     * @ORM\ManyToOne(targetEntity=InterventionReport::class, inversedBy="softwareInterventionReports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $intervention_report;

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
