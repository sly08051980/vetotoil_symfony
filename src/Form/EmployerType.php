<?php

namespace App\Form;

use App\Entity\Employer;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class EmployerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresse_employer', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control']
            ])
            ->add('complement_adresse_employer', TextType::class, [
                'label' => 'Complement Adresse',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('code_postal_employer', TextType::class, [
                'label' => 'Code Postal',
                'attr' => ['class' => 'form-control']
            ])
            ->add('ville_employer', TextType::class, [
                'label' => 'Ville',
                'attr' => ['class' => 'form-control']
            ])
            ->add('telephone_employer', TelType::class, [
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
            ->add('profession_employer', ChoiceType::class, [
                'label' => 'Profession',
                'choices' => [
                    'Choisissez...' => null,
                    'Vétérinaire' => 'Vétérinaire',
                    'Toiletteur' => 'Toiletteur',
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('images')
            ->add('date_creation_employer', DateType::class,[
                'attr' => [
                    'hidden' => true,
                    'label' => false,
                ]
              
            ]
            )

            ->add('submit', SubmitType::class,
            ['label'=>'Valider'
            ])
        ;
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $user = $event->getData();
       
            
            $user->setNom(validate_form($user->adresse_employer()));
            $user->setPrenom(validate_form($user->complement_adresse_employer()));
            $user->setPrenom(validate_form($user->code_postal_employer()));
            $user->setPrenom(validate_form($user->ville_employer()));
            $user->setPrenom(validate_form($user->telephone_employer()));
            $event->setData($user);
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employer::class,
           
        ]);
    }
}
