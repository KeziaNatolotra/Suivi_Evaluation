<?php

namespace App\Entity;

use App\Repository\ObjectifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObjectifRepository::class)]
class Objectif
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_objectif = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'objectifs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Mission $mission = null;

    /**
     * @var Collection<int, Activite>
     */
    #[ORM\OneToMany(targetEntity: Activite::class, mappedBy: 'objectif')]
    private Collection $activites;

    /**
     * @var Collection<int, ObjectifSpecifique>
     */
    #[ORM\OneToMany(targetEntity: ObjectifSpecifique::class, mappedBy: 'objectif')]
    private Collection $objectifSpecifiques;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
        $this->objectifSpecifiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomObjectif(): ?string
    {
        return $this->nom_objectif;
    }

    public function setNomObjectif(string $nom_objectif): static
    {
        $this->nom_objectif = $nom_objectif;

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

    public function getMission(): ?Mission
    {
        return $this->mission;
    }

    public function setMission(?Mission $mission): static
    {
        $this->mission = $mission;

        return $this;
    }

    /**
     * @return Collection<int, Activite>
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): static
    {
        if (!$this->activites->contains($activite)) {
            $this->activites->add($activite);
            $activite->setObjectif($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): static
    {
        if ($this->activites->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getObjectif() === $this) {
                $activite->setObjectif(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ObjectifSpecifique>
     */
    public function getObjectifSpecifiques(): Collection
    {
        return $this->objectifSpecifiques;
    }

    public function addObjectifSpecifique(ObjectifSpecifique $objectifSpecifique): static
    {
        if (!$this->objectifSpecifiques->contains($objectifSpecifique)) {
            $this->objectifSpecifiques->add($objectifSpecifique);
            $objectifSpecifique->setObjectif($this);
        }

        return $this;
    }

    public function removeObjectifSpecifique(ObjectifSpecifique $objectifSpecifique): static
    {
        if ($this->objectifSpecifiques->removeElement($objectifSpecifique)) {
            // set the owning side to null (unless already changed)
            if ($objectifSpecifique->getObjectif() === $this) {
                $objectifSpecifique->setObjectif(null);
            }
        }

        return $this;
    }
}
