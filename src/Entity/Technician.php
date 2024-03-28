<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\InterventionReport;
use App\Repository\TechnicianRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: TechnicianRepository::class)]
#[ORM\Table(name: "tbl_techncian")]
class Technician
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $last_name;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $first_name;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private ?string $email;

    #[ORM\ManyToMany(targetEntity: InterventionReport::class, mappedBy: "technicians")]
    private Collection $interventionReports;

    public function __construct()
    {
        $this->interventionReports = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->last_name . ' ' . $this->first_name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|InterventionReport[]
     */
    public function getInterventionReports(): Collection
    {
        return $this->interventionReports;
    }

    public function addInterventionReport(InterventionReport $interventionReport): self
    {
        if (!$this->interventionReports->contains($interventionReport)) {
            $this->interventionReports[] = $interventionReport;
            $interventionReport->addTechnician($this);
        }

        return $this;
    }

    public function removeInterventionReport(InterventionReport $interventionReport): self
    {
        if ($this->interventionReports->contains($interventionReport)) {
            $this->interventionReports->removeElement($interventionReport);
            $interventionReport->removeTechnician($this);
        }

        return $this;
    }
}
