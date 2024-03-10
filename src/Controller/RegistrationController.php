<?php

namespace App\Controller;

use App\Entity\Ajouter;

use App\Entity\Employer;
use App\Entity\Societe;
use App\Entity\User;
use App\Form\AjouterType;
use App\Form\RegistrationFormType;
use App\Repository\EmployerRepository;
use App\Repository\SocieteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $type = $request->request->get('type');

        $role = ['ROLE_USER'];

        if ($type === 'societe') {
            $role = ['ROLE_SOCIETE'];
        } else if ($type === 'patient') {
            $role = ['ROLE_PATIENT'];
        } else if ($type === 'employer') {
            $role = ['ROLE_EMPLOYER'];
        }
        $user->setRoles($role);
        $form = $this->createForm(RegistrationFormType::class, $user, [
            'type' => $type,


        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('_profiler_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,

        ]);
    }

    // #[Route('/test', name: 'find_test')]
    // public function some(SocieteRepository $societeRepository, Security $security, Request $request,EntityManagerInterface $entityManager): Response
    // {

    //     $user = $security->getUser();
    //     $email = $request->query->get('email');
    //     $nom = $request->request->get('nom');
    //     $prenom = $request->request->get('prenom');
        
       
          
    //         if ($user && in_array('ROLE_EMPLOYER', $user->getRoles())) {
    //             if (!$user) {

    //                 return $this->redirectToRoute('app_home'); 
    //             }
        
    //             $societe = $societeRepository->findSocieteByUser($user);
    //             if ($societe) {
    //                 $idSociete = $societe->getId();  
    //             $ajouter = new Ajouter();
    //             $ajouter->setDateEntreEmployer(new \DateTime());
    //             $ajouter->setSociete($societe);
    //             $form = $this->createForm(AjouterType::class,$ajouter);

    //             $form->handleRequest($request);
    //             if ($form->isSubmitted() && $form->isValid()) {
    //                 $ajouter=new Ajouter();
                    
    //                 $ajouter = $form->getData();
    //                  $entityManager->persist($ajouter);
    //                  $entityManager->flush();
    //             }
    //             return $this->render('registration/useremailresult.html.twig', [
    //                 'user' => $user,
    //                 'form' => $form->createView(),
    //             ]);
    //         }

    //     } 
    //     return $this->render('registration/findbyemail.html.twig', [
    //         'controller_name' => 'Recherche par Email',
    //     ]);
    // }
   
    // #[Route('findbyemail', name: 'find_by_email')]
    // public function findEmail(Request $request, EntityManagerInterface $entityManager,Security $security,SocieteRepository $societeRepository): Response
    // {

    //     $email = $request->query->get('email');
    //     $nom = $request->request->get('nom');
    //     $prenom = $request->request->get('prenom');
    //     $user = $security->getUser();
    //     if ($email) {
    //         $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
    //         $societe = $societeRepository->findSocieteByUser($user);
    //         $idSociete = $societe->getId();
    //         dump($idSociete);
    //             die;

    //         if ($user && in_array('ROLE_EMPLOYER', $user->getRoles())) {
               
    //             $ajouter = new Ajouter();
    //             $ajouter->setDateEntreEmployer(new \DateTime());
    //             $idSociete = $societe->getId();
    //             dump($idSociete);
    //             die;
    //             $form = $this->createForm(AjouterType::class,$ajouter);

    //             $form->handleRequest($request);
    //             if ($form->isSubmitted() && $form->isValid()) {
    //                 $ajouter=new Ajouter();
                    
    //                 $ajouter = $form->getData();

                 
                   
    //                  $entityManager->persist($ajouter);
    //                  $entityManager->flush();
    //             }

    //             return $this->render('registration/useremailresult.html.twig', [
    //                 'user' => $user,
    //                 'form' => $form->createView(),

    //             ]);
    //         } else {
    //             return $this->redirectToRoute('app_register', ['type' => 'employer']);
    //         }
    //     }

    //     return $this->render('registration/findbyemail.html.twig', [
    //         'controller_name' => 'Recherche par Email',
    //     ]);
    // }


  
}