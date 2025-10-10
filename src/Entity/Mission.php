<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_mission = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, Objectif>
     */
    #[ORM\OneToMany(targetEntity: Objectif::class, mappedBy: 'mission')]
    private Collection $objectifs;

    #[ORM\OneToOne(mappedBy: 'mission', cascade: ['persist', 'remove'])]
    private ?Departement $departement = null;

    public function __construct()
    {
        $this->objectifs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomMission(): ?string
    {
        return $this->nom_mission;
    }

    public function setNomMission(string $nom_mission): static
    {
        $this->nom_mission = $nom_mission;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Objectif>
     */
    public function getObjectifs(): Collection
    {
        return $this->objectifs;
    }

    public function addObjectif(Objectif $objectif): static
    {
        if (!$this->objectifs->contains($objectif)) {
            $this->objectifs->add($objectif);
            $objectif->setMission($this);
        }

        return $this;
    }

    public function removeObjectif(Objectif $objectif): static
    {
        if ($this->objectifs->removeElement($objectif)) {
            // set the owning side to null (unless already changed)
            if ($objectif->getMission() === $this) {
                $objectif->setMission(null);
            }
        }

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(Departement $departement): static
    {
        // set the owning side of the relation if necessary
        if ($departement->getMission() !== $this) {
            $departement->setMission($this);
        }

        $this->departement = $departement;

        return $this;
    }
}
