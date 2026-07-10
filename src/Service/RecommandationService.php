<?php

namespace App\Service;

use App\Entity\Materiel;
use App\Entity\Profil;
use App\Entity\Recommandation;
use App\Entity\Utilisateur;
use App\Repository\MaterielRepository;
use App\Repository\RecommandationRepository;
use Doctrine\ORM\EntityManagerInterface;

class RecommandationService
{
    public function __construct(
        private readonly MaterielRepository       $materielRepo,
        private readonly RecommandationRepository $recoRepo,
        private readonly EntityManagerInterface   $em
    ) {}

    public function genererPourUtilisateur(Utilisateur $user): array
    {
        $profil = $user->getProfil();

        if (!$profil || !$profil->isComplete()) {
            return [];
        }

        // Supprime les anciennes recommandations
        $this->recoRepo->deleteForUser($user);

        $recos = [];
        foreach ($this->materielRepo->findAllAvailable() as $materiel) {
            $score = $this->calculerScore($profil, $materiel);
            if ($score > 0.2) {
                $reco = new Recommandation();
                $reco->setUtilisateur($user);
                $reco->setMateriel($materiel);
                $reco->setScore($score);
                $reco->setExplication($this->genererExplication($profil, $materiel));
                $this->em->persist($reco);
                $recos[] = $reco;
            }
        }

        $this->em->flush();
        usort($recos, fn($a, $b) => $b->getScore() <=> $a->getScore());
        return array_slice($recos, 0, 20);
    }

    public function calculerScore(Profil $profil, Materiel $materiel): float
    {
        $score = 0.0;

        // Niveau — 40 pts
        $score += match(true) {
            $materiel->getNiveauRecommande() === $profil->getNiveau() => 40,
            $materiel->getNiveauRecommande() === 'tous'               => 30,
            $this->niveauProche($profil->getNiveau(), $materiel->getNiveauRecommande()) => 20,
            default => 5,
        };

        // Style de jeu → Flexibilité — 30 pts
        $style = $profil->getStyleJeu();
        $flex  = $materiel->getFlexibilite();
        $score += match(true) {
            $style === 'attaquant' && $flex === 'rigide'        => 30,
            $style === 'attaquant' && $flex === 'extra-rigide'  => 25,
            $style === 'defenseur' && $flex === 'flexible'      => 30,
            $style === 'defenseur' && $flex === 'medium'        => 20,
            $style === 'complet'   && $flex === 'medium'        => 30,
            $style === 'filet'     && in_array($flex, ['medium','flexible']) => 28,
            $flex === null => 15,
            default        => 10,
        };

        // Fréquence → Équilibre — 20 pts
        $freq = $profil->getFrequence();
        $eq   = $materiel->getEquilibre();
        $score += match(true) {
            $freq === 'intensif'    && $eq === 'tete-lourde' => 20,
            $freq === 'intensif'    && $eq === 'equilibre'   => 14,
            $freq === 'regulier'    && $eq === 'equilibre'   => 20,
            $freq === 'occasionnel' && $eq === 'equilibre'   => 20,
            $freq === 'occasionnel' && $eq === 'manche-lourd'=> 15,
            $eq === null => 10,
            default      => 8,
        };

        // Bonus type — 10 pts
        $score += in_array($materiel->getType(), ['raquette', 'cordage']) ? 10 : 5;

        return round(min($score, 100) / 100, 4);
    }

    private function niveauProche(?string $a, string $b): bool
    {
        $ordre = ['debutant' => 0, 'intermediaire' => 1, 'avance' => 2, 'expert' => 3];
        return $a !== null && abs(($ordre[$a] ?? 0) - ($ordre[$b] ?? 0)) === 1;
    }

    private function genererExplication(Profil $profil, Materiel $materiel): string
    {
        $raisons = [];

        if ($materiel->getNiveauRecommande() === $profil->getNiveau()) {
            $raisons[] = 'parfaitement adapté à votre niveau ' . $profil->getNiveauLabel();
        } elseif ($materiel->getNiveauRecommande() === 'tous') {
            $raisons[] = 'convient à tous les niveaux';
        }

        $style = $profil->getStyleJeu();
        $flex  = $materiel->getFlexibilite();

        if ($style === 'attaquant' && in_array($flex, ['rigide', 'extra-rigide'])) {
            $raisons[] = 'la tige rigide correspond à votre style attaquant';
        } elseif ($style === 'defenseur' && $flex === 'flexible') {
            $raisons[] = 'la flexibilité souple soutient votre jeu défensif';
        } elseif ($style === 'complet' && $flex === 'medium') {
            $raisons[] = "l'équilibre medium est idéal pour un jeu polyvalent";
        }

        if (empty($raisons)) {
            $raisons[] = 'compatible avec votre profil de joueur';
        }

        return ucfirst(implode(' et ', $raisons)) . '.';
    }
}