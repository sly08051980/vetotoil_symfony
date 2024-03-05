<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\Societe;
use App\Entity\Employer;
use App\Form\EmployerType;
use App\Form\PatientType;
use App\Form\SocieteType;
use App\Repository\EmployerRepository;
use App\Repository\PatientRepository;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FullRegistrationController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/full/registration', name: 'app_full_registration')]
    public function index(Security $security, PatientRepository $patientRepository, SocieteRepository $societeRepository, EmployerRepository $employerRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $security->getUser();
        if ($user && in_array('ROLE_PATIENT', $user->getRoles())) {
            $patient = $patientRepository->findOneBy(['user' => $user]);
            if ($patient) {
                return $this->redirectToRoute('app_home');
            } else {
                $user = new Patient();
                $user->setDateCreationPatient(new \DateTime());
                $users = $this->security->getUser();
                $user->setUser($users);
                $form = $this->createForm(PatientType::class, $user);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {

                    $entityManager->persist($user);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_home', ['patient' => $patient,]);
                }
                return $this->render('full_registration/patient.html.twig', [
                    'controller_name' => 'FullRegistrationController',
                    'form' => $form->createView(),
                ]);
            }
        } else if ($user && in_array('ROLE_SOCIETE', $user->getRoles())) {
            $societe = $societeRepository->findOneBy(['user' => $user]);
            if ($societe->getDateResiliationSociete()!==null){
                return $this->redirectToRoute('app_logout');
            }
            if ($societe) {
                if ($societe->getDateValidationSociete() == null) {
                    return $this->render('full_registration/societeattente.html.twig', [
                        'controller_name' => 'HomeController',
                    ]);
                } else {
                    return $this->redirectToRoute('app_home');
                }
            } else {
                $user = new Societe();
                $user->setDateCreationSociete(new \DateTime());
                $users = $this->security->getUser();
                $user->setUser($users);
                $form = $this->createForm(SocieteType::class, $user);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($user);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_home');
                }
                return $this->render('full_registration/societe.html.twig', [
                    'controller_name' => 'FullRegistrationController',
                    'form' => $form->createView(),
                ]);
            }
        } else if ($user && in_array('ROLE_EMPLOYER', $user->getRoles())) {
            $employer = $employerRepository->findOneBy(['user' => $user]);
            if ($employer) {
                return $this->redirectToRoute('app_route');
            } else {
                $user = new Employer();
                $user->setDateCreationEmployer(new \DateTime());
                $users = $this->security->getUser();
                $user->setUser($users);
                $form = $this->createForm(EmployerType::class,$user);
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($user);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_home');
                }
                return $this->render('full_registration/employer.html.twig', [
                    'controller_name' => 'FullRegistrationController',
                    'form' => $form->createView(),
                ]);
            }
        }

        return $this->render('full_registration/index.html.twig', [
            'controller_name' => 'FullRegistrationController',
        ]);
    }
}
