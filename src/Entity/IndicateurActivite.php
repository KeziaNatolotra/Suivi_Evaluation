<?php

namespace App\Entity;

use App\Repository\IndicateurActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IndicateurActiviteRepository::class)]
class IndicateurActivite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_indicateur = null;

    #[ORM\Column(length: 255)]
    private ?string $unite = null;

    #[ORM\Column]
    private ?float $valeur_actuelle = null;

    #[ORM\ManyToOne(inversedBy: 'indicateurActivites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Activite $activite = null;

    /**
     * @var Collection<int, Prevision>
     */
    #[ORM\OneToMany(targetEntity: Prevision::class, mappedBy: 'indicateurActivite')]
    private Collection $previsions;

    public function __construct()
    {
        $this->previsions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomIndicateur(): ?string
    {
        return $this->nom_indicateur;
    }

    public function setNomIndicateur(string $nom_indicateur): static
    {
        $this->nom_indicateur = $nom_indicateur;

        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(string $unite): static
    {
        $this->unite = $unite;

        return $this;
    }

    public function getValeurActuelle(): ?float
    {
        return $this->valeur_actuelle;
    }

    public function setValeurActuelle(float $valeur_actuelle): static
    {
        $this->valeur_actuelle = $valeur_actuelle;

        return $this;
    }

    public function getActivite(): ?Activite
    {
        return $this->activite;
    }

    public function setActivite(?Activite $activite): static
    {
        $this->activite = $activite;

        return $this;
    }

    /**
     * @return Collection<int, Prevision>
     */
    public function getPrevisions(): Collection
    {
        return $this->previsions;
    }

    public function addPrevision(Prevision $prevision): static
    {
        if (!$this->previsions->contains($prevision)) {
            $this->previsions->add($prevision);
            $prevision->setIndicateurActivite($this);
        }

        return $this;
    }

    public function removePrevision(Prevision $prevision): static
    {
        if ($this->previsions->removeElement($prevision)) {
            // set the owning side to null (unless already changed)
            if ($prevision->getIndicateurActivite() === $this) {
                $prevision->setIndicateurActivite(null);
            }
        }

        return $this;
    }
}
