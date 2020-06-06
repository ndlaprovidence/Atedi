<?php

namespace App\Entity;

use App\Repository\InterventionReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InterventionReportRepository::class)
 */
class InterventionReport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\OneToOne(targetEntity=Intervention::class, mappedBy="intervention_report", cascade={"persist", "remove"})
     */
    private $intervention;

    /**
     * @ORM\Column(type="integer")
     */
    private $step;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $severity;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $windows_install = [];

    /**
     * @ORM\ManyToMany(targetEntity=Booklet::class, inversedBy="interventionReports")
     */
    private $booklets;

    public function __construct()
    {
        $this->softwares = new ArrayCollection();
        $this->booklets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getIntervention(): ?Intervention
    {
        return $this->intervention;
    }

    public function setIntervention(Intervention $intervention): self
    {
        $this->intervention = $intervention;

        // set the owning side of the relation if necessary
        if ($intervention->getInterventionReport() !== $this) {
            $intervention->setInterventionReport($this);
        }

        return $this;
    }

    public function getStep(): ?int
    {
        return $this->step;
    }

    public function setStep(int $step): self
    {
        $this->step = $step;

        return $this;
    }

    public function getSeverity(): ?string
    {
        return $this->severity;
    }

    public function setSeverity(?string $severity): self
    {
        $this->severity = $severity;

        return $this;
    }

    public function getWindowsInstall(): ?array
    {
        return $this->windows_install;
    }

    public function setWindowsInstall(?array $windows_install): self
    {
        $this->windows_install = $windows_install;

        return $this;
    }

    /**
     * @return Collection|Booklet[]
     */
    public function getBooklets(): Collection
    {
        return $this->booklets;
    }

    public function addBooklet(Booklet $booklet): self
    {
        if (!$this->booklets->contains($booklet)) {
            $this->booklets[] = $booklet;
        }

        return $this;
    }

    public function removeBooklet(Booklet $booklet): self
    {
        if ($this->booklets->contains($booklet)) {
            $this->booklets->removeElement($booklet);
        }

        return $this;
    }
}
