<?php

namespace App\Entity;

use App\Repository\ObjectifspecifiqueRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObjectifspecifiqueRepository::class)]
class Objectifspecifique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_objectif_specifique = null;

    #[ORM\Column(length: 255)]
    private ?string $descritpion = null;

    #[ORM\Column]
    private ?float $taux = null;

    #[ORM\ManyToOne(inversedBy: 'objectifspecifiques')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Objectif $objectif = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomObjectifspecifique(): ?string
    {
        return $this->nom_objectif_specifique;
    }

    public function setNomObjectifspecifique(string $nom_objectif_specifique): static
    {
        $this->nom_objectif_specifique = $nom_objectif_specifique;

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

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(float $taux): static
    {
        $this->taux = $taux;

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
}
