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


    #[Route('/recherche', name: 'employer_search')]
    public function rechercheEmployer(Request $request, UserRepository $userRepository, SocieteRepository $societeRepository): Response
    {
        $email = $request->query->get('email');
        $user = $userRepository->findOneByEmail($email);
    
        if ($user && $user->getEmployer()) {
           
            $societe = $societeRepository->findOneByUser($this->getUser());
            
            if ($societe) {
                return $this->redirectToRoute('formulaire_ajouter', [
                    'employerId' => $user->getEmployer()->getId(),
                    'societeId' => $societe->getId(),
                ]);
            }
        }
    
        return $this->render('employer/search.html.twig');
    }
    #[Route('/ajouter', name: 'formulaire_ajouter')]
    public function ajouterInfo(Request $request, EmployerRepository $employerRepository, SocieteRepository $societeRepository, EntityManagerInterface $entityManager): Response
    {
        $employerId = $request->query->get('employerId');
        $societeId = $request->query->get('societeId');
    
        $ajouter = new Ajouter(); 
    
        $form = $this->createForm(AjouterType::class, $ajouter, [
            'employerId' => $employerId,
            'societeId' => $societeId,
        ]);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
           
            $employer = $employerRepository->find($employerId);
            $societe = $societeRepository->find($societeId);
            $ajouter->setEmployer($employer);
            $ajouter->setSociete($societe);
    
            $entityManager->persist($ajouter);
            $entityManager->flush();
    
       
            return $this->redirectToRoute('app_home');
        }
    
        return $this->render('registration/useremailresult.html.twig', [
            'form' => $form->createView(),
            'employerId' => $employerId,
            'societeId' => $societeId,
        ]);
    }
        
}