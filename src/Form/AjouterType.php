<?php

namespace App\Form;

use App\Entity\Ajouter;
use App\Entity\Employer;
use App\Entity\Societe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjouterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jours_travailler',ChoiceType::class,[
                'choices'=>[
                    'Lundi'=>'lundi',
                    'Mardi'=>'mardi',
                    'Mercredi'=>'mercredi',
                    'Jeudi'=>'jeudi',
                    'Vendredi'=>'vendredi',
                    'Samedi'=>'samedi',
                    'Dimanche'=>'dimanche',
                ],
                'multiple'=>true,
                'expanded'=>true,
            ])
            ->add('date_entre_employer', DateType::class, [
                
                'widget' => 'single_text',
                'data' => new \DateTime(),
            ])
            ->add('date_sortie_employer',DateType::class, [
                'label'=>'Photo Animal',
                'required' => false,
               
            ])
            ->add('date_debut_vacance',DateType::class, [
                'label'=>'Photo Animal',
                'required' => false,
               
            ])
            ->add('date_fin_vacance',DateType::class, [
                'label'=>'Photo Animal',
                'required' => false,
               
            ])
            ->add('debut_repas',DateType::class, [
                'label'=>'Photo Animal',
                'required' => false,
               
            ])
            ->add('date_fin_repas',DateType::class, [
                'label'=>'Photo Animal',
                'required' => false,
               
            ])



->add('employerId', TextType::class, [
    'data' => $options['employerId'],
    'mapped' => false,
])
->add('societeId', TextType::class, [
    'data' => $options['societeId'], 
    'mapped' => false,
])
            ->add('submit', SubmitType::class,
            ['label'=>'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ajouter::class,
            'employerId' => null,
            'societeId' => null,
        ]);
    }
}
