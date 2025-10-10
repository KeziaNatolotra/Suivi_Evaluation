<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_activite = null;

    #[ORM\Column(length: 255)]
    private ?string $descritpion = null;

    #[ORM\ManyToOne(inversedBy: 'activites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Objectif $objectif = null;

    /**
     * @var Collection<int, IndicateurActivite>
     */
    #[ORM\OneToMany(targetEntity: IndicateurActivite::class, mappedBy: 'activite')]
    private Collection $indicateurActivites;

    /**
     * @var Collection<int, Projet>
     */
    #[ORM\OneToMany(targetEntity: Projet::class, mappedBy: 'activite')]
    private Collection $projets;

    /**
     * @var Collection<int, Budget>
     */
    #[ORM\OneToMany(targetEntity: Budget::class, mappedBy: 'activite')]
    private Collection $budgets;

    public function __construct()
    {
        $this->indicateurActivites = new ArrayCollection();
        $this->projets = new ArrayCollection();
        $this->budgets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomActivite(): ?string
    {
        return $this->nom_activite;
    }

    public function setNomActivite(string $nom_activite): static
    {
        $this->nom_activite = $nom_activite;

        return $this;
    }

    public function getDescritpion(): ?string
    {
        return $this->descritpion;
    }

    public function setDescritpion(string $descritpion): static
    {
        $this->descritpion = $descritpion;

        return $this;
    }

    public function getObjectif(): ?Objectif
    {
        return $this->objectif;
    }

    public function setObjectif(?Objectif $objectif): static
    {
        $this->objectif = $objectif;

        return $this;
    }

    /**
     * @return Collection<int, IndicateurActivite>
     */
    public function getIndicateurActivites(): Collection
    {
        return $this->indicateurActivites;
    }

    public function addIndicateurActivite(IndicateurActivite $indicateurActivite): static
    {
        if (!$this->indicateurActivites->contains($indicateurActivite)) {
            $this->indicateurActivites->add($indicateurActivite);
            $indicateurActivite->setActivite($this);
        }

        return $this;
    }

    public function removeIndicateurActivite(IndicateurActivite $indicateurActivite): static
    {
        if ($this->indicateurActivites->removeElement($indicateurActivite)) {
            // set the owning side to null (unless already changed)
            if ($indicateurActivite->getActivite() === $this) {
                $indicateurActivite->setActivite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Projet>
     */
    public function getProjets(): Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): static
    {
        if (!$this->projets->contains($projet)) {
            $this->projets->add($projet);
            $projet->setActivite($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): static
    {
        if ($this->projets->removeElement($projet)) {
            // set the owning side to null (unless already changed)
            if ($projet->getActivite() === $this) {
                $projet->setActivite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Budget>
     */
    public function getBudgets(): Collection
    {
        return $this->budgets;
    }

    public function addBudget(Budget $budget): static
    {
        if (!$this->budgets->contains($budget)) {
            $this->budgets->add($budget);
            $budget->setActivite($this);
        }

        return $this;
    }

    public function removeBudget(Budget $budget): static
    {
        if ($this->budgets->removeElement($budget)) {
            // set the owning side to null (unless already changed)
            if ($budget->getActivite() === $this) {
                $budget->setActivite(null);
            }
        }

        return $this;
    }
}
