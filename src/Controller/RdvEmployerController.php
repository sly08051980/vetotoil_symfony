<?php

namespace App\Controller;

use App\Entity\Ajouter;
use App\Entity\Animal;
use App\Entity\Employer;
use App\Entity\Rdv;
use App\Form\RdvType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RdvEmployerController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route('/rdv/employer', name: 'app_rdv_employer')]
    public function rdvEmployer(EntityManagerInterface $entityManager): Response
    {

        $user = $this->security->getUser();
        $employer = $entityManager->getRepository(Employer::class)->findOneBy(['user' => $user]);
        $employerId = $employer->getId();
        if ($employerId) {

            $ajouter = $entityManager->getRepository(Ajouter::class)->findOneBy(['employer' => $employer]);


            $joursTravailArray = $ajouter->getJoursTravailler();

            $joursTravailNumerique = array_map(function ($jour) {
                return ['dimanche' => 0, 'lundi' => 1, 'mardi' => 2, 'mercredi' => 3, 'jeudi' => 4, 'vendredi' => 5, 'samedi' => 6,][strtolower($jour)];
            }, $joursTravailArray);

            $dateDebut = new \DateTime();
            $dateFin = clone $dateDebut;
            $dateFin->modify('+7 days');
            while ($dateDebut <= $dateFin) {
                if (in_array((int) $dateDebut->format('w'), $joursTravailNumerique)) {
                    $dateTravail = $dateDebut->format('Y-m-d');
                    $rdvs = $entityManager->getRepository(Rdv::class)->findBy(['date_rdv' => $dateDebut, 'employer' => $employer, 'status_rdv' => null]);


                    for ($heure = 8; $heure <= 17; $heure++) {
                        $creneauxDisponibles[$dateTravail][sprintf("%02d:00 - %02d:00", $heure, $heure + 1)] = 'Libre';
                    }


                    foreach ($rdvs as $rdv) {
                        $heureRdv = (int) $rdv->getHeureRdv()->format('H'); // Convertir en entier pour assurer la correspondance

                        // Construire la clé pour le créneau horaire pris
                        $creneauKey = sprintf("%02d:00 - %02d:00", $heureRdv, $heureRdv + 1);

                        // Vérifier si la clé existe bien pour éviter les erreurs
                        if (isset ($creneauxDisponibles[$dateTravail][$creneauKey])) {
                            //recupere les entity pour afficher les donnée
                            $patient = $rdv->getPatient();
                            $userPatient = $patient->getUser();
                            $animal = $rdv->getAnimal();
                            $animalPatient = $animal->getPrenomAnimal();
                            $race = $animal->getRace();
                            $type = $race->getType();

                            $infoPatient = [
                                $patient->getId(),
                                $patient->getTelephonePatient(),
                                $userPatient->getEmail(),
                                $userPatient->getNom(),
                                $userPatient->getPrenom(),
                                $animalPatient,
                                $race->getRaceAnimal(),
                                $type->getTypeAnimal(),
                            ];
                            //fin de recupere les entity pour afficher les donnée


                            // S'il existe, marquer comme pris avec des détails
                            // Ajoutez ici les informations que vous souhaitez afficher
                            $creneauxDisponibles[$dateTravail][$creneauKey] = [
                                'status' => 'Pris',
                                'rdv' => $rdv,
                                'infoPatient' => $infoPatient,
                            ];
                        }
                    }
                }
                $dateDebut->modify('+1 day');
            }
// dd($creneauxDisponibles);
        }


        return $this->render('rdv_employer/index.html.twig', [
            'controller_name' => 'RdvEmployerController',
            'creneauxDisponible'=>$creneauxDisponibles,
        ]);
    }
 
    #[Route('/rdv/employer/{id}/edit/{status}', name: 'app_rdv_employer_annuler', methods: ['GET', 'POST'])]
    public function editRdvEmployer(Request $request,Rdv $rdv,EntityManagerInterface $entityManager, string $status): Response
    {
        if ($status === 'annuler') {
           
            $rdv->setStatusRdv('annuler');
            
        }else if($status === 'valider'){
            $rdv->setStatusRdv('valider');
        }
      
$entityManager->flush();
return $this->redirectToRoute('app_rdv_employer', [], Response::HTTP_SEE_OTHER);

        
    
    }
}
