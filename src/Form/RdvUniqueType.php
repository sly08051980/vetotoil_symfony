
<?php

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RdvUniqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
         
            ->add('societeId', TextType::class, [
                'data' => $options['societeId'],
            ])
            ->add('employeId', TextType::class, [
                'data' => $options['employeId'],
            ])
            ->add('userId', TextType::class, [
                'data' => $options['userId'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Autres options par défaut...
            'societeId' => null,
            'employeId' => null,
            'userId' => null,
        ]);
    }
}
