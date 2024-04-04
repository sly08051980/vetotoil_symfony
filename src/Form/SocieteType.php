<?php

namespace App\Form;

use App\Entity\Societe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SocieteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('siret', TelType::class, [
            'label' => 'Siret',
            'constraints' => [
                new Length(['min' => 14, 'max' => 14]),
                new Regex([
                    'pattern' => '/^\d{14}$/',
                    'message' => 'Le numéro SIRET doit contenir exactement 14 chiffres.',
                ]),
            ],
            'attr' => [
                'class' => 'form-control',
                'maxlength' => 14, 
                'pattern' => '\\d{14}', 
            ]
        ])
            ->add('nom_societe', TextType::class, [
                'label' => 'Nom de la Société',
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
                'label' => 'Téléphone société',
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
                'label' => 'Téléphone dirigeant',
                'constraints' => [
                    new Length(['min' => 10, 'max' => 10]),
                    new Regex([
                        'pattern' => '/^\d{10}$/',
                        'message' => 'Le numéro de téléphone doit contenir exactement 10 chiffres.',
                    ]),
                ],
                'attr' => ['class' => 'form-control']
                ])
                ->add('imageFile',VichImageType::class,[
                    'label'=>'Photo Societe',
                    'required' => false,
                    'label_attr'=>[
                        'class'=>'form-label mt-4'
                    ]
                ])
            ->add('date_creation_societe',DateType::class, [
                'label' => 'Date de création',
                // 'data' => new \DateTime(), 
                // 'disabled' => true, 
            ])
            ->add('submit', SubmitType::class,
['label'=>'Valider'
])
        ;
        // $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
        //     $user = $event->getData();
        
        //     $user->setSiret(validate_form($user->getSiret()));
        //     $user->setNomSociete(validate_form($user->getNomSociete()));
        //     $user->setAdresseSociete(validate_form($user->getAdresseSociete()));
        //     $user->setComplementAdresseSociete(validate_form($user->getComplementAdresseSociete()));
        //     $user->setCodePostalSociete(validate_form($user->getCodePostalSociete()));
        //     $user->setVilleSociete(validate_form($user->getVilleSociete()));
        //     $user->setTelephoneSociete(validate_form($user->getTelephoneSociete()));
        //     $user->setTelephoneDirigeant(validate_form($user->getTelephoneDirigeant()));
        
        //     $event->setData($user);
        // });
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Societe::class,
        ]);
    }
}
