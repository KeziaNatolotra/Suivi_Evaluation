<?php

namespace App\Entity;

use App\Repository\PrevisionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrevisionRepository::class)]
class Prevision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $T1 = null;

    #[ORM\Column]
    private ?float $T2 = null;

    #[ORM\Column]
    private ?float $T3 = null;

    #[ORM\Column]
    private ?float $T4 = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column(length: 255)]
    private ?string $LFR_LFI = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_physique = null;

    #[ORM\ManyToOne(inversedBy: 'previsions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?IndicateurActivite $indicateurActivite = null;

    /**
     * @var Collection<int, ResultatAttendu>
     */
    #[ORM\OneToMany(targetEntity: ResultatAttendu::class, mappedBy: 'prevision')]
    private Collection $resultatAttendus;

    /**
     * @var Collection<int, ResultatObtenu>
     */
    #[ORM\OneToMany(targetEntity: ResultatObtenu::class, mappedBy: 'prevision')]
    private Collection $resultatObtenus;

    public function __construct()
    {
        $this->resultatAttendus = new ArrayCollection();
        $this->resultatObtenus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getT1(): ?float
    {
        return $this->T1;
    }

    public function setT1(float $T1): static
    {
        $this->T1 = $T1;

        return $this;
    }

    public function getT2(): ?float
    {
        return $this->T2;
    }

    public function setT2(float $T2): static
    {
        $this->T2 = $T2;

        return $this;
    }

    public function getT3(): ?float
    {
        return $this->T3;
    }

    public function setT3(float $T3): static
    {
        $this->T3 = $T3;

        return $this;
    }

    public function getT4(): ?float
    {
        return $this->T4;
    }

    public function setT4(float $T4): static
    {
        $this->T4 = $T4;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getLFRLFI(): ?string
    {
        return $this->LFR_LFI;
    }

    public function setLFRLFI(string $LFR_LFI): static
    {
        $this->LFR_LFI = $LFR_LFI;

        return $this;
    }

    public function getDatePhysique(): ?\DateTime
    {
        return $this->date_physique;
    }

    public function setDatePhysique(\DateTime $date_physique): static
    {
        $this->date_physique = $date_physique;

        return $this;
    }

    public function getIndicateurActivite(): ?IndicateurActivite
    {
        return $this->indicateurActivite;
    }

    public function setIndicateurActivite(?IndicateurActivite $indicateurActivite): static
    {
        $this->indicateurActivite = $indicateurActivite;

        return $this;
    }

    /**
     * @return Collection<int, ResultatAttendu>
     */
    public function getResultatAttendus(): Collection
    {
        return $this->resultatAttendus;
    }

    public function addResultatAttendu(ResultatAttendu $resultatAttendu): static
    {
        if (!$this->resultatAttendus->contains($resultatAttendu)) {
            $this->resultatAttendus->add($resultatAttendu);
            $resultatAttendu->setPrevision($this);
        }

        return $this;
    }

    public function removeResultatAttendu(ResultatAttendu $resultatAttendu): static
    {
        if ($this->resultatAttendus->removeElement($resultatAttendu)) {
            // set the owning side to null (unless already changed)
            if ($resultatAttendu->getPrevision() === $this) {
                $resultatAttendu->setPrevision(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ResultatObtenu>
     */
    public function getResultatObtenus(): Collection
    {
        return $this->resultatObtenus;
    }

    public function addResultatObtenu(ResultatObtenu $resultatObtenu): static
    {
        if (!$this->resultatObtenus->contains($resultatObtenu)) {
            $this->resultatObtenus->add($resultatObtenu);
            $resultatObtenu->setPrevision($this);
        }

        return $this;
    }

    public function removeResultatObtenu(ResultatObtenu $resultatObtenu): static
    {
        if ($this->resultatObtenus->removeElement($resultatObtenu)) {
            // set the owning side to null (unless already changed)
            if ($resultatObtenu->getPrevision() === $this) {
                $resultatObtenu->setPrevision(null);
            }
        }

        return $this;
    }
}
