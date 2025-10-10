<?php

namespace App\Entity;

use App\Repository\ResultatObtenuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultatObtenuRepository::class)]
class ResultatObtenu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $descritpion = null;

    #[ORM\Column(length: 255)]
    private ?string $indicateur = null;

    #[ORM\Column]
    private ?float $valeur_actuelle = null;

    #[ORM\ManyToOne(inversedBy: 'resultatObtenus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prevision $prevision = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIndicateur(): ?string
    {
        return $this->indicateur;
    }

    public function setIndicateur(string $indicateur): static
    {
        $this->indicateur = $indicateur;

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

    public function getPrevision(): ?Prevision
    {
        return $this->prevision;
    }

    public function setPrevision(?Prevision $prevision): static
    {
        $this->prevision = $prevision;

        return $this;
    }
}
