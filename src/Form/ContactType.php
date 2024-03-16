<?php
namespace App\Form;

use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sujet', ChoiceType::class, [
                'choices' => [
                    'Choisissez...' => null,
                    'Inscription Particulier' => 'particulierIns',
                    'Inscription Professionnel' => 'ProfessionnelIns',
                    'Connexion Professionnel' => 'ParticulierCon',
                    'Connexion Particulier' => 'ProfessionnelCon',
                    'RGPD' => 'rgpd',
                    'Mention Légale' => 'mentionlegale',
                    'Autres' => 'autres',
                ],
                'label' => 'Sujet :',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'attr' => ['rows' => 12],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => ['class' => 'btn btn-custom rounded-pill'],
                'label' => 'Envoyé',
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
              
            ])
            ;
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          
        ]);
    }
}
