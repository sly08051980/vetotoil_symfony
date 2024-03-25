<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Employer;
use App\Entity\Patient;
use App\Entity\Societe;
use App\Entity\Soigner;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoignerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('acte_soins')
            ->add('traitement')
            ->add('nombre_fois')
            ->add('date_soins', null, [
                'widget' => 'single_text',
            ])
            ->add('societe', EntityType::class, [
                'class' => Societe::class,
                'choice_label' => 'id',
                'data'=>$options['societe'],
            ])
            ->add('employer', EntityType::class, [
                'class' => Employer::class,
                'choice_label' => 'id',
            ])
            ->add('patient', EntityType::class, [
                'class' => Patient::class,
                'choice_label' => 'id',
            ])
            ->add('animal', EntityType::class, [
                'class' => Animal::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Soigner::class,
            'societe'=>null,
        ]);
    }
}
