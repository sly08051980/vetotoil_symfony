<?php

namespace App\Form;

use App\Entity\Societe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SocieteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('siret')
            ->add('nom_societe')
            ->add('profession_societe')
            ->add('adresse_societe')
            ->add('complement_adresse_societe')
            ->add('code_postal_societe')
            ->add('ville_societe')
            ->add('telephone_societe')
            ->add('telephone_dirigeant')
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
