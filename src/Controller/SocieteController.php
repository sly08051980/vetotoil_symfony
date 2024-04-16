<?php

namespace App\Controller;

use App\Entity\Ajouter;
use App\Entity\Employer;
use App\Entity\Rdv;
use App\Entity\Societe;
use App\Entity\User;
use App\Form\SocieteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SocieteController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/societe/employer', name: 'app_societe_employer')]
    public function index(Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();
        $societe = $user->getSociete();
        $societeId = $societe->getId();
        $findAllEmployer = $entityManager->getRepository(Ajouter::class)->findBy(['societe' => $societeId]);
        $employerDetails = [];
        foreach ($findAllEmployer as $employerEntity) {
            $employer = $employerEntity->getEmployer();
            if ($employer) {
                $user = $employer->getUser();
                if ($user) {
                    $employerDetails[] = [
                        'id' => $employer->getId(),
                        'email' => $user->getEmail(),
                        'nom' => $user->getNom(),
                        'prenom' => $user->getPrenom(),
                        'adresse' => $employer->getAdresseEmployer(),
                        'complement_adresse' => $employer->getComplementAdresseEmployer(),
                        'code_postal' => $employer->getCodePostalEmployer(),
                        'ville' => $employer->getVilleEmployer(),
                        'telephone' => $employer->getTelephoneEmployer(),
                        'profession' => $employer->getProfessionEmployer(),

                    ];
                }
            }
        }


        return $this->render('societe/index.html.twig', [
            'controller_name' => 'SocieteController',
            'employerDetails' => $employerDetails,
        ]);
    }
    #[Route('/societe/employer/rdv', name: 'app_societe_employer_rdv')]
    public function rdvTousEmployer(Security $security, EntityManagerInterface $entityManager)
    {
        $user = $security->getUser();
        $societe = $entityManager->getRepository(Societe::class)->findOneBy(['user' => $user]);
        $employerEntities = $entityManager->getRepository(Ajouter::class)->findBy(['societe' => $societe]);
    
        $employerDetails = []; 
        $dateJour = new \DateTime();
        $horaires = range(8, 17);
        foreach ($employerEntities as $employerEntity) {
            $employer = $employerEntity->getEmployer();
    
            if ($employer) {
                $userEmployer = $employer->getUser(); 
                $id = $employer->getId();
                $creneaux = array_fill_keys($horaires, 'Libre');
    
                $rdvEmployer = $entityManager->getRepository(Rdv::class)->findBy([
                    'employer' => $id,
                    'status_rdv' => null
                ]);
    
                foreach ($rdvEmployer as $rdv) {
                    if ($rdv->getDateRdv()->format('Y-m-d') === $dateJour->format('Y-m-d')) {
                        $heureRdv = (int)$rdv->getHeureRdv()->format('G');
    
                        if (array_key_exists($heureRdv, $creneaux)) {
                            $patient = $rdv->getPatient(); 
                            $patientUser = $patient->getUser(); 
                            
                            if ($patientUser) {
                                $creneaux[$heureRdv] = $patientUser->getPrenom() . ' ' . $patientUser->getNom(); 
                            } else {
                                $creneaux[$heureRdv] = 'Patient introuvable';
                            }
                        }
                    }
                }
    
                $employerDetails[] = [
                    'id' => $id,
                    'nom' => $userEmployer->getNom(), 
                    'prenom' => $userEmployer->getPrenom(), 
                    'rdvs' => $creneaux,
                ];
            }
        }
        
        return $this->render('societe/employerrdv.html.twig', [
            'employerDetails' => $employerDetails,
        ]);
    }
    #[Route('/societe/profil', name: 'app_societe_profil')]
    public function update(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $this->security->getUser();
        $userId = $user->getId();
        $edit = $entityManager->getRepository(Societe::class)->findOneBy(['user' => $userId]);
        if (!$edit) {
            throw $this->createNotFoundException('Personne non trouvée.');
        }
        $form = $this->createForm(SocieteType::class, $edit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('societe/modifier.html.twig', [
            'form' => $form->createView(),
        ]);


    }
    #[Route('/societe/supprimer',name:'app_societe_supprimer')]
    public function supprimer(Request $request,EntityManagerInterface $entityManager){
        $identifiant = $request->request->get('identifiant');
       
        $ajouter=$entityManager->getRepository(Ajouter::class)->findOneBy(['employer'=>$identifiant]);


        $ajouter->setDateSortieEmployer(new \DateTime());

    
        $entityManager->persist($ajouter);
        $entityManager->flush(); 
    

        return $this->redirectToRoute('app_home');
    }
}
