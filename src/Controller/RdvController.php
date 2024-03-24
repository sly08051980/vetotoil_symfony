<?php

namespace App\Controller;

use App\Entity\Ajouter;
use App\Entity\Animal;
use App\Entity\Commune;
use App\Entity\Employer;
use App\Entity\Patient;
use App\Entity\Rdv;
use App\Entity\Societe;
use App\Entity\User;
use App\Form\RdvType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use RdvUniqueType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\SecurityBundle\Security;

class RdvController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/rdv/find/{typePro}', name: 'app_rdv_find')]
    public function index(Request $request, EntityManagerInterface $entityManager,$typePro): Response
    {
      
        $user = $this->security->getUser();
        $userId = $user->getId();
        $patient = $entityManager->getRepository(Patient::class)->findOneBy(['user' => $userId]);
        $codePostalPatient = $patient->getCodePostalPatient();
        $communePatient = $entityManager->getRepository(Commune::class)->findOneBy(['code_postal_commune' => $codePostalPatient]);
        $animaux = $entityManager->getRepository(Animal::class)->findBy(['user' => $user]);


       
       

        $disponibilites = [];
        $joursCorrespondance = [
            'lundi' => 'monday',
            'mardi' => 'tuesday',
            'mercredi' => 'wednesday',
            'jeudi' => 'thursday',
            'vendredi' => 'friday',
            'samedi' => 'saturday',
            'dimanche' => 'sunday',
        ];

        if ($communePatient) {
            $latitudePatient = $communePatient->getLatitude();
            $longitudePatient = $communePatient->getLongitude();
            $communesProches = $this->findCommunesWithinRadius($latitudePatient, $longitudePatient, 10, $entityManager);

            $codesPostauxProches = array_map(function ($commune) {
                return $commune['code_postal_commune'];
            }, $communesProches);

            $societesProches = $entityManager->getRepository(Societe::class)->findBy(['code_postal_societe' => $codesPostauxProches]);
            $employesFiltres = [];

            foreach ($societesProches as $societe) {
                $ajoutsSociete = $entityManager->getRepository(Ajouter::class)->findBy(['societe' => $societe]);
                foreach ($ajoutsSociete as $ajout) {
                    $employe = $ajout->getEmployer();
                    if ((null === $ajout->getDateSortieEmployer() || new DateTime() < $ajout->getDateSortieEmployer()) && $ajout->getEmployer()->getProfessionEmployer() === $typePro) {
                        $employesFiltres[(string) $employe->getId()] = $employe;
                    }
                }
            }
          
            foreach ($employesFiltres as $employeId => $employe) {
                $trouve = false; // Variable pour suivre si une disponibilité a été trouvée pour cet employé
            
                // Récupérer les informations des sociétés pour cet employé
                $societesInfo = [];
                $ajoutsEmploye = $entityManager->getRepository(Ajouter::class)->findBy(['employer' => $employe]);
                foreach ($ajoutsEmploye as $ajout) {
                    $societe = $ajout->getSociete();
                    if ($societe) {
                        $societesInfo[] = [
                            'nomSociete' => $societe->getNomSociete(),
                            'adresseSociete' => $societe->getAdresseSociete(),
                            'codePostalSociete' => $societe->getCodePostalSociete(),
                            'idSociete' => $societe->getId(),
                        ];
                    }
                }
            
                // Récupérer les jours de travail de l'employé
                $joursTravailEmploye = []; // Tableau pour stocker les jours de travail
                foreach ($ajoutsEmploye as $ajout) {
                    if ($ajout->getJoursTravailler()) {
                        foreach ($ajout->getJoursTravailler() as $jour) {
                            $joursTravailEmploye[] = $joursCorrespondance[strtolower($jour)];
                        }
                    }
                }
            
                for ($i = 1; $i <= 31 && !$trouve; $i++) {
                    $date = (new DateTime())->modify("+$i day");
                    $jourDeLaSemaine = strtolower($date->format('l'));
            
                    // Vérifier si le jour actuel est un jour de travail pour l'employé
                    if (in_array($jourDeLaSemaine, $joursTravailEmploye)) {
                        $rdvs = $employe->getRdvs()->filter(function ($rdv) use ($date) {
                            return $rdv->getDateRdv()->format('Y-m-d') === $date->format('Y-m-d');
                        });
            
                        $horairesOccupes = array_map(function ($rdv) {
                            return (int)$rdv->getHeureRdv()->format('H');
                        }, $rdvs->toArray());
            
                        $horairesTravail = range(8, 17);
                        foreach ($horairesTravail as $heure) {
                            if (!in_array($heure, $horairesOccupes)) {
                                $disponibilites[$employeId] = [
                                    'date' => $date->format('Y-m-d'),
                                    'heure' => $heure,
                                    'employeId' => $employeId,
                                    'nom' => $employe->getUser()->getNom(),
                                    'prenom' => $employe->getUser()->getPrenom(),
                                    'societes' => $societesInfo,
                                ];
                                $trouve = true; // Marquez qu'une disponibilité a été trouvée
                                break; // Sortez de la boucle des heures dès qu'une disponibilité est trouvée
                            }
                        }
                    }
                }
            }
        }
        $form = $this->createForm(RdvUniqueType::class);
        $form->handleRequest($request);

        return $this->render('rdv/findrdv.html.twig', [
            'controller_name' => 'RdvController',
            'disponibilites' => $disponibilites,
            'patient' => $patient,
            'animaux'=>$animaux,
            'userId'=>$userId,
            'typePro' => $typePro,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/rdvtest/valid', name: 'app_rdv_valid')]
    public function rdvValid(Request $request, EntityManagerInterface $entityManager)
    {
        if ($request->isMethod('POST')) {
           
            $societeEmployerRdv=$request->request->get('societeId');
            $date = $request->request->get('date');
            $heure = $request->request->get('heure');
            $employeId = $request->request->get('employeId');
            $userId = $request->request->get('userId');
            $animalId = $request->request->get('animal');

          
            $patient = $entityManager->getRepository(Patient::class)->findOneBy(['user' => $userId]);
            $societe = $entityManager->getRepository(Societe::class)->find($societeEmployerRdv);
            $animal = $entityManager->getRepository(Animal::class)->find($animalId);
            $employer=$entityManager->getRepository(Employer::class)->find($employeId);
            // Créer une nouvelle instance de Rdv
            $rdv = new Rdv();
            $rdv->setDateRdv(new \DateTime($date));
            $rdv->setHeureRdv(new \DateTime($heure));
            $rdv->setEmployer($employer);
            
            $rdv->setSociete($societe);
            $rdv->setAnimal($animal);
        
            // Définir le patient pour le Rdv
            $rdv->setPatient($patient);
            $entityManager->persist($rdv);
            $entityManager->flush();
            $this->addFlash('info', 'Rendez vous bien pris');
            return $this->redirectToRoute('app_home');
        } else {
            return new Response("Méthode non autorisée", Response::HTTP_METHOD_NOT_ALLOWED);
        }
    }


    #[Route('/rdv/findpro', name:'app_rdv_find_pro')]
    public function findPro(Request $request, EntityManagerInterface $entityManager): Response
    {
        $creneauxDisponibles = [];
    
        $user = $this->security->getUser();
        $userId = $user->getId();
        $patient = $entityManager->getRepository(Patient::class)->findOneBy(['user' => $userId]);
        $animaux = $entityManager->getRepository(Animal::class)->findBy(['user' => $user]);
      $typePro="";
        if ($request->isMethod('POST')) {
            $typePro = $request->request->get('typePro');
         
            $employeId = $request->request->get('employeId');
    
            return $this->redirectToRoute('app_rdv_find_pro', [
                'employeId' => $employeId,
                'typePro'=>$typePro,
            ]);
        }
        
    $societeInfo=null;
   
        $employeId = $request->query->get('employeId');
        $typePro = $request->query->get('typePro');
        if ($employeId) {
            $employe = $entityManager->getRepository(Employer::class)->find($employeId);
   
            if ($employe && $employe->getProfessionEmployer() === $typePro) {
    
                $ajouter = $entityManager->getRepository(Ajouter::class)->findOneBy(['employer' => $employe]);
                if ($ajouter && $ajouter->getSociete()) {
                    $societe = $ajouter->getSociete();
                    $societeInfo = [
                        'nomSociete' => $societe->getNomSociete(),
                        'adresseSociete' => $societe->getAdresseSociete(),
                        'codePostalSociete' => $societe->getCodePostalSociete(),
                        'idSociete' => $societe->getId(),
                    ];
                    $joursTravailArray = $ajouter->getJoursTravailler();
    
                    $dateDebut = new \DateTime(); // Date de début (à ajuster selon le besoin)
                    $dateFin = clone $dateDebut;
                    $dateFin->modify('+1 month'); // Ajoute un mois pour définir la date de fin
    
                    // Conversion des jours de travail en leur équivalent numérique
                    $joursTravailNumerique = array_map(function ($jour) {
                        return ['dimanche' => 0, 'lundi' => 1, 'mardi' => 2, 'mercredi' => 3, 'jeudi' => 4, 'vendredi' => 5, 'samedi' => 6,][strtolower($jour)];
                    }, $joursTravailArray);
    
                    // Parcours de l'intervalle jour par jour
                    while ($dateDebut <= $dateFin) {
                        if (in_array((int)$dateDebut->format('w'), $joursTravailNumerique)) {
                            $dateTravail = $dateDebut->format('Y-m-d');
                            $rdvs = $entityManager->getRepository(Rdv::class)->findBy(['date_rdv' => $dateDebut, 'employer' => $employe]);
    
                            $rdvsParHeure = [];
                            foreach ($rdvs as $rdv) {
                                $rdvsParHeure[$rdv->getHeureRdv()->format('H')] = true;
                            }
    
                            for ($heure = 8; $heure <= 17; $heure++) {
                                if (!isset($rdvsParHeure[str_pad($heure, 2, '0', STR_PAD_LEFT)])) {
                                    $creneauxDisponibles[$dateTravail][] = sprintf("%02d:00 - %02d:00", $heure, $heure + 1);
                                }
                            }
                        }
                        $dateDebut->modify('+1 day');
                    }
                }
            }
        }
   
        return $this->render('rdv/findrdvparemployer.html.twig', [
            'controller_name' => 'RdvController',
            'creneauxDisponibles' => $creneauxDisponibles,
            'patient' => $patient,
            'animaux' => $animaux,
            'societeInfo' => $societeInfo,
            'userId' => $userId,
            'employeId' => $employeId,
        ]);
    }


    #[Route('/rdv/myrdv', name:'app_rdv_my_rdv')]
    public function myRdv(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->security->getUser();
        $userId = $user->getId();
        $patient = $entityManager->getRepository(Patient::class)->findOneBy(['user' => $userId]);
        $rdvs = $entityManager->getRepository(Rdv::class)->findBy(['patient' => $patient]);
        $rdvsFutur = [];
        $rdvsPasser = [];
        $today = new \DateTime();
        
        foreach ($rdvs as $rdv) {
            if ($rdv->getDateRdv() >= $today) {
                $rdvsFutur[] = $rdv;
              
            } else {
                $rdvsPasser[] = $rdv;
            
            }
        }

     
        return $this->render('rdv/mesrdvs.html.twig', [
            'rdvsFutur' => $rdvsFutur,
            'rdvsPasser' => $rdvsPasser,
            // autres variables nécessaires
        ]);

    }




    public function findCommunesWithinRadius($latitude, $longitude, $radius = 10, EntityManagerInterface $entityManager)
    {
        $conn = $entityManager->getConnection();
        $sql = "
            SELECT id, nom_commune, code_postal_commune,
                   (6366 * acos(cos(radians(:lat)) * cos(radians(latitude)) * cos(radians(longitude) - radians(:lon)) + sin(radians(:lat)) * sin(radians(latitude)))) AS distance
            FROM commune
            HAVING distance < :distance
            ORDER BY distance
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('lat', $latitude);
        $stmt->bindValue('lon', $longitude);
        $stmt->bindValue('distance', $radius);
        $result = $stmt->execute();
        $results = $result->fetchAllAssociative();

        return $results;
    }
}
