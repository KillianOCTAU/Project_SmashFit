<?php

namespace App\Entity;

use App\Repository\MarqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarqueRepository::class)]
#[ORM\Table(name: 'marque')]
class Marque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    private ?string $nomMarque = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $pays = null;

    #[ORM\OneToMany(mappedBy: 'marque', targetEntity: Materiel::class)]
    private Collection $materiels;

    public function __construct()
    {
        $this->materiels = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNomMarque(): ?string { return $this->nomMarque; }
    public function setNomMarque(string $v): static { $this->nomMarque = $v; return $this; }

    public function getPays(): ?string { return $this->pays; }
    public function setPays(?string $v): static { $this->pays = $v; return $this; }

    public function getMateriels(): Collection { return $this->materiels; }
}