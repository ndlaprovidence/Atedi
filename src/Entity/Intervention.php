<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InterventionRepository")
 */
class Intervention
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $deposit_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $return_date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OperatingSystem", inversedBy="interventions")
     */
    private $operating_system;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Equipment", inversedBy="interventions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $equipment;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Technician", inversedBy="interventions")
     */
    private $technicians;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Task", inversedBy="interventions")
     */
    private $tasks;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Client", inversedBy="interventions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    public function __construct()
    {
        $this->technicians = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->setDepositDate(new \DateTime());
        $this->setStatus('En cours');
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepositDate(): ?\DateTimeInterface
    {
        return $this->deposit_date;
    }

    public function setDepositDate(\DateTimeInterface $deposit_date): self
    {
        $this->deposit_date = $deposit_date;

        return $this;
    }

    public function getReturnDate(): ?\DateTimeInterface
    {
        return $this->return_date;
    }

    public function setReturnDate(?\DateTimeInterface $return_date): self
    {
        $this->return_date = $return_date;

        return $this;
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

    public function getOperatingSystem(): ?OperatingSystem
    {
        return $this->operating_system;
    }

    public function setOperatingSystem(?OperatingSystem $operating_system): self
    {
        $this->operating_system = $operating_system;

        return $this;
    }

    public function getEquipment(): ?Equipment
    {
        return $this->equipment;
    }

    public function setEquipment(?Equipment $equipment): self
    {
        $this->equipment = $equipment;

        return $this;
    }

    /**
     * @return Collection|Technician[]
     */
    public function getTechnicians(): Collection
    {
        return $this->technicians;
    }

    public function addTechnician(Technician $technician): self
    {
        if (!$this->technicians->contains($technician)) {
            $this->technicians[] = $technician;
        }

        return $this;
    }

    public function removeTechnician(Technician $technician): self
    {
        if ($this->technicians->contains($technician)) {
            $this->technicians->removeElement($technician);
        }

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
        }

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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
}
