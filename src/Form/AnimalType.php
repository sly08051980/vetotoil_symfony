<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Race;
use App\Entity\Type;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('prenom_animal', TextType::class, [
                'label' => 'Prenom animal',
                'attr' => ['class' => 'form-control']
            ])
            ->add('date_naissance_animal', DateType::class, [
                'widget' => 'single_text', 
                'attr' => ['class' => 'form-control'], 
                'label' => 'Date de naissance', 

            ])
            ->add('date_creation_animal', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                // 'label' => false, 
                // 'attr' => ['hidden' => true], 
            ])
            ->add('date_fin_animal', DateType::class, [
                'widget' => 'single_text',
                'label' => false,
                'attr' => ['hidden' => true],
                'required' => false,

            ])
            ->add('type', EntityType::class, [
                'class' => Type::class,
'choice_label' => 'type_animal',
'placeholder' => 'Choisissez un type',
'attr' => ['id' => 'typeSelect'],
'mapped' => false,
            ])
            ->add('race', EntityType::class, [
                'class' => Race::class,
'choice_label' => 'raceAnimal',
'placeholder' => 'Choisissez une race',
'attr' => ['id' => 'raceSelect'],
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
'choice_label' => 'id',
// 'attr' => ['style' => 'display:none;'],
'label' => 'id user',
'data' => $user,

            ])
            ->add('imageFile',VichImageType::class,[
                'label'=>'Photo Animal',
                'required' => false,
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ]
            ])
            ->add('submit', SubmitType::class,
            ['label'=>'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
            'user' => null,
        ]);
    }
}
