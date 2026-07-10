<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\Materiel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom du produit'])
            ->add('type', ChoiceType::class, [
                'label'   => 'Type',
                'choices' => array_combine(
                    array_map('ucfirst', Materiel::TYPES),
                    Materiel::TYPES
                ),
            ])
            ->add('marque', EntityType::class, [
                'label'        => 'Marque',
                'class'        => Marque::class,
                'choice_label' => 'nomMarque',
                'placeholder'  => '-- Sélectionnez une marque --',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr'  => ['rows' => 4],
            ])
            ->add('imageUrl', UrlType::class, [
                'label'    => "URL de l'image",
                'required' => false,
            ])
            ->add('poids', TextType::class, [
                'label'    => 'Poids (ex: 85g)',
                'required' => false,
            ])
            ->add('niveauRecommande', ChoiceType::class, [
                'label'   => 'Niveau recommandé',
                'choices' => [
                    'Tous niveaux'  => 'tous',
                    'Débutant'      => 'debutant',
                    'Intermédiaire' => 'intermediaire',
                    'Avancé'        => 'avance',
                    'Expert'        => 'expert',
                ],
            ])
            ->add('equilibre', ChoiceType::class, [
                'label'       => 'Équilibre',
                'required'    => false,
                'placeholder' => '-- Non applicable --',
                'choices'     => [
                    'Tête lourde' => 'tete-lourde',
                    'Équilibré'   => 'equilibre',
                    'Manche lourd'=> 'manche-lourd',
                ],
            ])
            ->add('flexibilite', ChoiceType::class, [
                'label'       => 'Flexibilité',
                'required'    => false,
                'placeholder' => '-- Non applicable --',
                'choices'     => [
                    'Flexible'    => 'flexible',
                    'Medium'      => 'medium',
                    'Rigide'      => 'rigide',
                    'Extra-rigide'=> 'extra-rigide',
                ],
            ])
            ->add('materiau', TextType::class, [
                'label'    => 'Matériau',
                'required' => false,
            ])
            ->add('prix', MoneyType::class, [
                'label'    => 'Prix',
                'currency' => 'EUR',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Materiel::class]);
    }
}