<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $type = $request->request->get('type');
        $role = ['ROLE_USER'];
       
        if ($type === 'societe') {
            $role = ['ROLE_SOCIETE'];
        }else if($type ==='patient'){
            $role = ['ROLE_PATIENT'];
        }else if($type ==='employer'){
            $role = ['ROLE_EMPLOYER'];
        }
        $user->setRoles($role);
        $form = $this->createForm(RegistrationFormType::class, $user, [
            'type' => $type, 
        ]);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // La logique pour déterminer le rôle est déjà faite, pas besoin de la répéter
          
    
            // Encodage du mot de passe
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
    
            $entityManager->persist($user);
            $entityManager->flush();
    
            // Redirection après enregistrement
            return $this->redirectToRoute('_profiler_home');
        }
    
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
    
}
