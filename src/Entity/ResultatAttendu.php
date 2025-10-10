<?php

namespace App\Entity;

use App\Repository\ResultatAttenduRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResultatAttenduRepository::class)]
class ResultatAttendu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $indicateur = null;

    #[ORM\Column(length: 255)]
    private ?float $valeur_cible = null;

    #[ORM\Column]
    private ?float $manque_a_gagner = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_evaluation = null;

    #[ORM\ManyToOne(inversedBy: 'resultatAttendus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Prevision $prevision = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIndicateur(): ?string
    {
        return $this->indicateur;
    }

    public function setIndicateur(string $indicateur): static
    {
        $this->indicateur = $indicateur;

        return $this;
    }

    public function getValeurCible(): ?float
    {
        return $this->valeur_cible;
    }

    public function setValeurCible(float $valeur_cible): static
    {
        $this->valeur_cible = $valeur_cible;

        return $this;
    }

    public function getManqueAGagner(): ?float
    {
        return $this->manque_a_gagner;
    }

    public function setManqueAGagner(float $manque_a_gagner): static
    {
        $this->manque_a_gagner = $manque_a_gagner;

        return $this;
    }

    public function getDateEvaluation(): ?\DateTime
    {
        return $this->date_evaluation;
    }

    public function setDateEvaluation(\DateTime $date_evaluation): static
    {
        $this->date_evaluation = $date_evaluation;

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
