<?php

namespace App\Form;

use App\Entity\Patient;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('adresse_patient', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control']
            ])
            ->add('complement_adresse_patient', TextType::class, [
                'label' => 'Complement Adresse',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('code_postal_patient', TextType::class, [
                'label' => 'Code Postal',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 5, 
                    'pattern' => '\\d*', 
                ],
                'constraints' => [
                    new Length([
                        'max' => 5,
                        'maxMessage' => 'Le code postal ne peut contenir plus de {{ limit }} chiffres.',
                    ]),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Le code postal doit être composé uniquement de chiffres.',
                    ]),
                ],
            ])
            ->add('ville_patient', TextType::class, [
                'label' => 'Ville',
                'attr' => ['class' => 'form-control']
            ])
            ->add('telephone_patient', TextType::class, [
                'label' => 'Téléphone Patient',
                'attr' => [
                    'class' => 'form-control',
                    'maxlength' => 10, 
                    'pattern' => '\\d*', 
                ],
                'constraints' => [
                    new Length([
                        'max' => 5,
                        'maxMessage' => 'Le téléphone ne peut contenir plus de {{ limit }} chiffres.',
                    ]),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Le téléphone doit être composé uniquement de chiffres.',
                    ]),
                ],
            ])
            ->add('date_creation_patient', DateType::class,[
                'label' => false,
                'attr' => [
                    'hidden' => true,
                   
                ]
              
            ]
            )
            ->add('imageFile',VichImageType::class,[
                'label'=>'Photo',
                'required' => false,
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ]
            ])
            // ->add('date_fin_patient', DateType::class,[
            //     'attr' => [
            //         'hidden' => true,
            //         'label' => false,
            //         'required' => false,
            //     ]
              
            // ]
            // )
            ->add('submit', SubmitType::class,
['label'=>'Valider'
])

        ;
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $user = $event->getData();
       
            
            $user->setAdressePatient(validate_form($user->getAdressePatient()));
            $user->setComplementAdressePatient(validate_form($user->getComplementAdressePatient()));
            $user->setCodePostalPatient(validate_form($user->getCodePostalPatient()));
            $user->setVillePatient(validate_form($user->getVillePatient()));
            $user->setTelephonePatient(validate_form($user->getTelephonePatient()));
            $event->setData($user);
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
