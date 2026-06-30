<?php

namespace App\Entity;

use App\Repository\MaterielRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MaterielRepository::class)]
#[ORM\Table(name: 'materiel')]
class Materiel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 150)]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 30)]
    private ?string $type = null;

    #[ORM\Column(type: 'text')]
    private ?string $description = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $imageUrl = null;

    #[ORM\Column(type: 'string', length: 10, nullable: true)]
    private ?string $poids = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $equilibre = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $flexibilite = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $materiau = null;

    #[ORM\Column(type: 'string', length: 20)]
    private string $niveauRecommande = 'tous';

    #[ORM\Column(type: 'decimal', precision: 8, scale: 2, nullable: true)]
    private ?string $prix = null;

    #[ORM\ManyToOne(inversedBy: 'materiels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Marque $marque = null;

    #[ORM\OneToMany(mappedBy: 'materiel', targetEntity: Recommandation::class)]
    private Collection $recommandations;

    public function __construct()
    {
        $this->recommandations = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $v): static { $this->nom = $v; return $this; }

    public function getType(): ?string { return $this->type; }
    public function setType(string $v): static { $this->type = $v; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $v): static { $this->description = $v; return $this; }

    public function getImageUrl(): ?string { return $this->imageUrl; }
    public function setImageUrl(?string $v): static { $this->imageUrl = $v; return $this; }

    public function getPoids(): ?string { return $this->poids; }
    public function setPoids(?string $v): static { $this->poids = $v; return $this; }

    public function getEquilibre(): ?string { return $this->equilibre; }
    public function setEquilibre(?string $v): static { $this->equilibre = $v; return $this; }

    public function getFlexibilite(): ?string { return $this->flexibilite; }
    public function setFlexibilite(?string $v): static { $this->flexibilite = $v; return $this; }

    public function getMateriau(): ?string { return $this->materiau; }
    public function setMateriau(?string $v): static { $this->materiau = $v; return $this; }

    public function getNiveauRecommande(): string { return $this->niveauRecommande; }
    public function setNiveauRecommande(string $v): static { $this->niveauRecommande = $v; return $this; }

    public function getPrix(): ?string { return $this->prix; }
    public function setPrix(?string $v): static { $this->prix = $v; return $this; }

    public function getMarque(): ?Marque { return $this->marque; }
    public function setMarque(?Marque $v): static { $this->marque = $v; return $this; }

    public function getRecommandations(): Collection { return $this->recommandations; }
}