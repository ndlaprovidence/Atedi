<?php

namespace App\Entity;

use App\Entity\Intervention;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PropsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PropsRepository")
 * @UniqueEntity(fields={"title"}, message="Il existe déjà un matériel avec ce nom")
 * @ORM\Table(name="tbl_props")
 */
class Props
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
    private $title;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Intervention", mappedBy="props")
     */
    private $interventions;

    public function __construct()
    {
        $this->interventions = new ArrayCollection();
    }

    public function __toString()
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


    public function getInterventions(): Collection
    {
        return $this->interventions;
    }
    
    public function addIntervention(Intervention $intervention): self
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions[] = $intervention;
            $intervention->addProps($this); // Ajoute le Props à la liste des Props de l'intervention
        }
    
        return $this;
    }
    
    public function removeIntervention(Intervention $intervention): self
    {
        if ($this->interventions->contains($intervention)) {
            $this->interventions->removeElement($intervention);
            $intervention->removeProps($this); // Retire le Props de la liste des Props de l'intervention
        }
    
        return $this;
    }
}
