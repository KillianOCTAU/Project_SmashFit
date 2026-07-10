<?php

namespace App\Form;

use App\Entity\Profil;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label'    => 'Nom',
                'required' => false,
            ])
            ->add('prenom', TextType::class, [
                'label'    => 'Prénom',
                'required' => false,
            ])
            ->add('age', IntegerType::class, [
                'label'    => 'Âge',
                'required' => false,
            ])
            ->add('club', TextType::class, [
                'label'    => 'Club',
                'required' => false,
                'attr'     => [
                    'id'           => 'club-input',
                    'autocomplete' => 'off',
                    'placeholder'  => 'Tapez pour rechercher...',
                ],
            ])
            ->add('numeroLicence', TextType::class, [
                'label'    => 'Numéro de licence FFBaD',
                'required' => false,
                'attr'     => ['id' => 'licence-input'],
            ])
            ->add('niveau', ChoiceType::class, [
                'label'       => 'Niveau de jeu',
                'required'    => false,
                'placeholder' => '-- Sélectionnez votre niveau --',
                'choices'     => [
                    'Débutant'      => 'debutant',
                    'Intermédiaire' => 'intermediaire',
                    'Avancé'        => 'avance',
                    'Expert'        => 'expert',
                ],
            ])
            ->add('styleJeu', ChoiceType::class, [
                'label'       => 'Style de jeu',
                'required'    => false,
                'placeholder' => '-- Sélectionnez votre style --',
                'choices'     => [
                    'Attaquant'      => 'attaquant',
                    'Défenseur'      => 'defenseur',
                    'Joueur complet' => 'complet',
                    'Jeu au filet'   => 'filet',
                ],
            ])
            ->add('frequence', ChoiceType::class, [
                'label'       => 'Fréquence de pratique',
                'required'    => false,
                'placeholder' => '-- Sélectionnez votre fréquence --',
                'choices'     => [
                    'Occasionnel (< 1×/semaine)' => 'occasionnel',
                    'Régulier (1-3×/semaine)'    => 'regulier',
                    'Intensif (> 3×/semaine)'    => 'intensif',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Profil::class]);
    }
}