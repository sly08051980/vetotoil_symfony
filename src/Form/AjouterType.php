<?php

namespace App\Form;

use App\Entity\Ajouter;
use App\Entity\Employer;
use App\Entity\Societe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AjouterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jours_travailler')
            ->add('date_entre_employer')
            ->add('date_sortie_employer')
            ->add('date_debut_vacance')
            ->add('date_fin_vacance')
            ->add('debut_repas')
            ->add('date_fin_repas')
            ->add('societe', EntityType::class, [
                'class' => Societe::class,
'choice_label' => 'id',
            ])
            ->add('employer', EntityType::class, [
                'class' => Employer::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ajouter::class,
        ]);
    }
}
