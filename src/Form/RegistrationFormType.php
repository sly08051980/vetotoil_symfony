<?php

namespace App\Form;

use App\Entity\User;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $defaultRoles = match ($options['type']) {
            'societe' => ['ROLE_SOCIETE'],
            'patient' => ['ROLE_PATIENT'],
            'employer' => ['ROLE_EMPLOYER'],
            default => ['ROLE_USER'],
        };
    

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control']
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'form-control']
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prenom',
                'attr' => ['class' => 'form-control']
            ])
            ->add('agreeTerms', CheckboxType::class, [
                                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Password','attr' => ['class' => 'form-control']],
                'second_options' => ['label' => 'Repeat Password','attr' => ['class' => 'form-control']],
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class,
            ['label'=>'Valider'
            ])
            ->add('roles', ChoiceType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'd-none',
                ],
                'choices' => [
                    'Patient' => 'ROLE_PATIENT',
                    'Société' => 'ROLE_SOCIETE',
                    'Employé' => 'ROLE_EMPLOYER',
                    
                ],
                'expanded' => true,
                'multiple' => true,
                'data' => $defaultRoles,
               
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
              
            ])
            ;
            $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                $user = $event->getData();
           
                
                $user->setNom(validate_form($user->getNom()));
                $user->setPrenom(validate_form($user->getPrenom()));
                $user->setEmail(email_form($user->getEmail()));
                $event->setData($user);
            });
    
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'type' => null, 
        
           
        ]);
    }
}
