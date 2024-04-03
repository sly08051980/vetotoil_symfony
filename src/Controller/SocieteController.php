<?php

namespace App\Controller;

use App\Entity\Ajouter;
use App\Entity\Employer;
use App\Entity\Rdv;
use App\Entity\Societe;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SocieteController extends AbstractController
{
    #[Route('/societe', name: 'app_societe')]
    public function index(Security $security,EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();
        $societe=$user->getSociete();
        $societeId=$societe->getId();
        $findAllEmployer=$entityManager->getRepository(Ajouter::class)->findBy(['societe'=>$societeId]);
       
        $employerId=[];
        foreach($findAllEmployer as $findAllEmployers){
            $employer=$findAllEmployers->getEmployer();
            if($employer){
                $employerId[]=[$employer->getId(),
              $employer->getUser(),
            ];
            }
        }
      
        dd($employerId);
        return $this->render('societe/index.html.twig', [
            'controller_name' => 'SocieteController',
        ]);
    }
    #[Route('/societe/employer/rdv', name: 'app_societe_employer_rdv')]
public function rdvTousEmployer(Security $security,EntityManagerInterface $entityManager){
$user=$security->getUser();
$societe=$entityManager->getRepository(Societe::class)->findOneBy(['user'=>$user]);
$employer=$entityManager->getRepository(Ajouter::class)->findBy(['societe'=>$societe]);
// $rdv=$entityManager->getRepository(Rdv::class)->findBy(['employer'=>$employer]);
$employerId = [];
$rdvs = [];


foreach ($employer as $employers) {

    $employerId[] = (string) $employers->getEmployer()->getId(); 
}


$dateJour = new \DateTime();
foreach ($employerId as $id) {
   
    $idEmployer = (string) $id;

    $rdvEmployer = $entityManager->getRepository(Rdv::class)->findBy(['employer' => $idEmployer,'date_rdv'=>$dateJour,'status_rdv' => null]);
    
  
        $rdvs[$idEmployer] = $rdvEmployer;
        for ($heure = 8; $heure <= 17; $heure++) {
         
            $creneauxDisponibles[sprintf("%02d:00 - %02d:00", $heure, $heure + 1)] = 'Libre';
        }
        
    
}


dd($creneauxDisponibles);
}
}
