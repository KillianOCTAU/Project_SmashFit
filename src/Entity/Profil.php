<?php

namespace App\Entity;

use App\Repository\ProfilRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfilRepository::class)]
#[ORM\Table(name: 'profil')]
class Profil
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'profil', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $age = null;

    #[ORM\Column(type: 'string', length: 150, nullable: true)]
    private ?string $club = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $numeroLicence = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private ?string $niveau = null;

    #[ORM\Column(type: 'string', length: 30, nullable: true)]
    private ?string $styleJeu = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $frequence = null;

    public function getId(): ?int { return $this->id; }

    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(?Utilisateur $u): static { $this->utilisateur = $u; return $this; }

    public function getNom(): ?string { return $this->nom; }
    public function setNom(?string $v): static { $this->nom = $v; return $this; }

    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(?string $v): static { $this->prenom = $v; return $this; }

    public function getAge(): ?int { return $this->age; }
    public function setAge(?int $v): static { $this->age = $v; return $this; }

    public function getClub(): ?string { return $this->club; }
    public function setClub(?string $v): static { $this->club = $v; return $this; }

    public function getNumeroLicence(): ?string { return $this->numeroLicence; }
    public function setNumeroLicence(?string $v): static { $this->numeroLicence = $v; return $this; }

    public function getNiveau(): ?string { return $this->niveau; }
    public function setNiveau(?string $v): static { $this->niveau = $v; return $this; }

    public function getStyleJeu(): ?string { return $this->styleJeu; }
    public function setStyleJeu(?string $v): static { $this->styleJeu = $v; return $this; }

    public function getFrequence(): ?string { return $this->frequence; }
    public function setFrequence(?string $v): static { $this->frequence = $v; return $this; }
}