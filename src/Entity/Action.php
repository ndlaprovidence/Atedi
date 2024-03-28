<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\InterventionReport;
use App\Repository\ActionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
#[UniqueEntity(fields: ['title'], message: 'Il existe déjà une action avec ce nom')]
#[ORM\Table(name: 'tbl_action')]
class Action
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $title = null;

    #[ORM\ManyToMany(targetEntity: InterventionReport::class, mappedBy: 'actions')]
    private Collection $interventionReports;

    public function __construct()
    {
        $this->interventionReports = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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
            $interventionReport->addAction($this);
        }

        return $this;
    }

    public function removeInterventionReport(InterventionReport $interventionReport): self
    {
        if ($this->interventionReports->contains($interventionReport)) {
            $this->interventionReports->removeElement($interventionReport);
            $interventionReport->removeAction($this);
        }

        return $this;
    }
}
