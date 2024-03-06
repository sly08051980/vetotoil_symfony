<?php

namespace App\Form;

use App\Entity\Societe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class SocieteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('siret', TelType::class, [
                'label' => 'Numéro de téléphone',
                'constraints' => [
                    new Length(['min' => 10, 'max' => 14]),
                    new Regex([
                        'pattern' => '/^\d{10}$/',
                        'message' => 'Le numéro de téléphone doit contenir exactement 10 chiffres.',
                    ]),
                ],
                'attr' => ['class' => 'form-control']
                ])
            ->add('nom_societe', TextType::class, [
                'label' => 'Complement Adresse',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('profession_societe', ChoiceType::class, [
                'label' => 'Profession',
                'choices' => [
                    'Choisissez...' => null,
                    'Vétérinaire' => 'Vétérinaire',
                    'Toiletteur' => 'Toiletteur',
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('adresse_societe', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control']
            ])
            ->add('complement_adresse_societe', TextType::class, [
                'label' => 'Complement Adresse',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('code_postal_societe', TextType::class, [
                'label' => 'Code Postal',
                'attr' => ['class' => 'form-control']
            ])
            ->add('ville_societe', TextType::class, [
                'label' => 'Ville',
                'attr' => ['class' => 'form-control']
            ])
            ->add('telephone_societe', TelType::class, [
                'label' => 'Numéro de téléphone',
                'constraints' => [
                    new Length(['min' => 10, 'max' => 10]),
                    new Regex([
                        'pattern' => '/^\d{10}$/',
                        'message' => 'Le numéro de téléphone doit contenir exactement 10 chiffres.',
                    ]),
                ],
                'attr' => ['class' => 'form-control']
                ])
            ->add('telephone_dirigeant', TelType::class, [
                'label' => 'Numéro de téléphone',
                'constraints' => [
                    new Length(['min' => 10, 'max' => 10]),
                    new Regex([
                        'pattern' => '/^\d{10}$/',
                        'message' => 'Le numéro de téléphone doit contenir exactement 10 chiffres.',
                    ]),
                ],
                'attr' => ['class' => 'form-control']
                ])
            ->add('images')
            ->add('date_creation_societe',DateType::class, [
                'label' => 'Date de création',
                // 'data' => new \DateTime(), 
                // 'disabled' => true, 
            ])
            ->add('submit', SubmitType::class,
['label'=>'Valider'
])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Societe::class,
        ]);
    }
}
