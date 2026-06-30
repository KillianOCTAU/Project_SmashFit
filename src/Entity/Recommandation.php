<?php

namespace App\Entity;

use App\Repository\RecommandationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecommandationRepository::class)]
#[ORM\Table(name: 'recommandation')]
class Recommandation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'recommandations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Materiel $materiel = null;

    #[ORM\Column(type: 'float')]
    private float $score = 0.0;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $dateReco;

    public function __construct()
    {
        $this->dateReco = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }

    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(?Utilisateur $v): static { $this->utilisateur = $v; return $this; }

    public function getMateriel(): ?Materiel { return $this->materiel; }
    public function setMateriel(?Materiel $v): static { $this->materiel = $v; return $this; }

    public function getScore(): float { return $this->score; }
    public function setScore(float $v): static { $this->score = $v; return $this; }

    public function getDateReco(): \DateTimeImmutable { return $this->dateReco; }
}