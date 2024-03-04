<?php

namespace App\Form;

use App\Entity\Patient;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('adresse_patient')
            ->add('complement_adresse_patient')
            ->add('code_postal_patient')
            ->add('ville_patient')
            ->add('telephone_patient')
            ->add('date_creation_patient')
            ->add('date_fin_patient')
            ->add('submit', SubmitType::class,
['label'=>'Valider'
])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
    }
}
