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

    #[Route('/rdv', name: 'app_rdv')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
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
                    if ((null === $ajout->getDateSortieEmployer() || new DateTime() < $ajout->getDateSortieEmployer()) && $ajout->getEmployer()->getProfessionEmployer() === 'Vétérinaire') {
                        $employesFiltres[(string) $employe->getId()] = $employe;
                    }
                }
            }
          
            foreach ($employesFiltres as $employeId => $employe) {
                $trouve = false; // Variable pour suivre si une disponibilité a été trouvée pour cet employé
                $societesInfo = [];
                $ajoutsEmploye = $entityManager->getRepository(Ajouter::class)->findBy(['employer' => $employe]);
                foreach ($ajoutsEmploye as $ajout) {
                    $societe = $ajout->getSociete();
                    if ($societe) {
                        $societesInfo[] = [
                            'nomSociete' => $societe->getNomSociete(),
                            'adresseSociete' => $societe->getAdresseSociete(),
                            'codePostalSociete' => $societe->getCodePostalSociete(),
                            'idSociete'=>$societe->getId(),
                        ];
                    }
                }
                for ($i = 1; $i <= 31 && !$trouve; $i++) { // Ajouter la condition !$trouve pour arrêter la boucle une fois une disponibilité trouvée
                    $date = (new DateTime())->modify("+$i day");
                    $jourActuelEnFrancais = array_search(strtolower($date->format('l')), array_map('strtolower', $joursCorrespondance), true);
            
                    $rdvs = $employe->getRdvs()->filter(function ($rdv) use ($date) {
                        return $rdv->getDateRdv()->format('Y-m-d') === $date->format('Y-m-d');
                    });
            
                    $horairesOccupes = array_map(function ($rdv) {
                        return (int)$rdv->getHeureRdv()->format('H');
                    }, $rdvs->toArray());
            
                    $horairesTravail = range(8, 17);
                    foreach ($horairesTravail as $heure) {
                        if (!in_array($heure, $horairesOccupes)) {
                            $disponibilites[$employeId] = [ // Utilisez l'ID de l'employé comme clé pour assurer l'unicité
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
        $form = $this->createForm(RdvUniqueType::class);
        $form->handleRequest($request);

        return $this->render('rdv/findrdv.html.twig', [
            'controller_name' => 'RdvController',
            'disponibilites' => $disponibilites,
            'patient' => $patient,
            'animaux'=>$animaux,
            'userId'=>$userId,
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


#[Route ('/rdv/findpro',name:'app_rdv_find_pro') ]
public function findPro(Request $request,EntityManagerInterface $entityManager):Response{
    $disponibilites = [];
if ($request->isMethod('POST')) {
   

    $employeId = $request->request->get('employeId');
    $employer=$entityManager->getRepository(Employer::class)->find($employeId);
    $rdvs = $entityManager->getRepository(Rdv::class)->findByEmployerBetweenDates($employer);
dd($rdvs);


             
            
        }
    



return $this->render('rdv/findrdvparemployer.html.twig', [
    'controller_name' => 'RdvController',
    'disponibilites'=>$disponibilites,

    
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
