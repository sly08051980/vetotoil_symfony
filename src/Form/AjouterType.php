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
//             ->add('jours_travailler',ChoiceType::class,[
//                 'choices'=>[
//                     'Lundi'=>'lundi',
//                     'Mardi'=>'mardi',
//                     'Mercredi'=>'mercredi',
//                     'Jeudi'=>'jeudi',
//                     'Vendredi'=>'vendredi',
//                     'Samedi'=>'samedi',
//                     'Dimanche'=>'dimanche',
//                 ],
//                 'multiple'=>true,
//                 'expanded'=>true,
//             ])
//             ->add('date_entre_employer', DateType::class, [
//                 // Options pour date_entre_employer, par exemple :
//                 'widget' => 'single_text',
//             ])
//             ->add('date_sortie_employer')
//             ->add('date_debut_vacance')
//             ->add('date_fin_vacance')
//             ->add('debut_repas')
//             ->add('date_fin_repas')
//             ->add('societe', EntityType::class, [
//                 'class' => Societe::class,
//                 'choice_label' => 'id',
//             ])
//             ->add('employer', EntityType::class, [
//                 'class' => Employer::class,
// 'choice_label' => 'id',
//             ])
//             ->add('submit', SubmitType::class,
//             ['label'=>'Valider'
//             ])

->add('employerId', TextType::class, [
    'data' => $options['employerId'],
    'mapped' => false,
])
            ->add('societeId', TextType::class, [
                'data' => $options['employerId'],
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
