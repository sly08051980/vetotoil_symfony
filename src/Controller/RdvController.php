<?php

namespace App\Controller;

use App\Entity\Ajouter;
use App\Entity\Animal;
use App\Entity\Employer;
use App\Entity\Patient;
use App\Entity\Rdv;

use App\Entity\Societe;
use App\Form\RdvType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class RdvController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/rdv', name: 'app_rdv')]
    public function index(Request $request,EntityManagerInterface $entityManager,Security $security): Response
    {
        $employes = $entityManager->getRepository(Employer::class)->findAll();
        $disponibilites = [];

        $user = $this->security->getUser();
        $userId = $user->getId();
        $edit = $entityManager->getRepository(Patient::class)->findOneBy(['user' => $userId]);
        $animal = $entityManager->getRepository(Animal::class)->findBy(['user'=>$user]);
        $form = $this->createForm(RdvType::class);
        $form->handleRequest($request);
       
      
        $joursCorrespondance = [
            'lundi' => 'monday',
            'mardi' => 'tuesday',
            'mercredi' => 'wednesday',
            'jeudi' => 'thursday',
            'vendredi' => 'friday',
            'samedi' => 'saturday',
            'dimanche' => 'sunday',
        ];

        foreach ($employes as $employe) {
            $ajouter = $entityManager->getRepository(Ajouter::class)->findBy(['employer' => $employe]);
        
            $joursTravail = [];
          
            for ($i = 1; $i <= 31; $i++) {
               
                $date = (new DateTime())->modify("+$i day");
                $jourActuelEnFrancais = array_search(strtolower($date->format('l')), array_map('strtolower', $joursCorrespondance), true);
        
                foreach ($ajouter as $a) {
                    if (in_array($jourActuelEnFrancais, array_map('strtolower', $a->getJoursTravailler()))) {
                        $joursTravail[] = $date->format('Y-m-d');
                    }
                }
            }
        
            $joursTravail = array_unique($joursTravail);
        
            $horairesTravail = range(8, 17);
        
            $rdvs = $employe->getRdvs()->toArray();
            usort($rdvs, function ($a, $b) {
                return $a->getDateRdv() <=> $b->getDateRdv() ?: $a->getHeureRdv() <=> $b->getHeureRdv();
            });
        
            foreach ($joursTravail as $jour) {
                $trouve = false;
                foreach ($horairesTravail as $heure) {
                    $creneauLibre = true;
                    foreach ($rdvs as $rdv) {
                        if ($rdv->getDateRdv()->format('Y-m-d') == $jour && (int)$rdv->getHeureRdv()->format('H') == $heure) {
                            $creneauLibre = false;
                            break;
                        }
                    }
                    if ($creneauLibre) {
                        $disponibilites[(string)$employe->getId()] = [$jour, $heure];
                        $trouve = true;
                        break;
                    }
                }
                if ($trouve) break;
            }
        }

       
        
        return $this->render('rdv/findrdv.html.twig', [
            'controller_name' => 'RdvController',
            'disponibilites' => $disponibilites,
            'employes'=>$employes,
            'edit'=>$edit,
            'form' => $form->createView(),
            
        ]);
    }
}