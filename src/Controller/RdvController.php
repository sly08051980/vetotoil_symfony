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
    public function index(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $employes = $entityManager->getRepository(Employer::class)->findAll();
        $disponibilites = [];
        $nom = "";
        $prenom = "";

        $user = $this->security->getUser();
        $userId = $user->getId();
        $patient = $entityManager->getRepository(Patient::class)->findOneBy(['user' => $userId]);
        $animal = $entityManager->getRepository(Animal::class)->findBy(['user' => $user]);
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
        $codePostalPatient = $patient->getCodePostalPatient();

        $communePatient = $entityManager->getRepository(Commune::class)->findOneBy(['code_postal_commune' => $codePostalPatient]);

        if ($communePatient) {
            $latitudePatient = $communePatient->getLatitude();
            $longitudePatient = $communePatient->getLongitude();
            $communes = $this->findCommunesWithinRadius($latitudePatient, $longitudePatient, 10, $entityManager);


            foreach ($employes as $employe) {
                if ($employe->getProfessionEmployer() !== 'Vétérinaire') {
                    continue;
                }
                $codesPostaux = array_map(function ($commune) {
                    return $commune['code_postal_commune'];
                }, $communes);

                $ajouterRechercheCommunes = $entityManager->getRepository(Societe::class)->findBy(['code_postal_societe' => $codesPostaux]);

                $ajouterTrie = [];
                foreach ($ajouterRechercheCommunes as $societe) {
                 
                    // Obtenir les employés pour chaque société trouvée
                    $employesDeLaSociete = $entityManager->getRepository(Ajouter::class)->findBy(['societe' => $societe]);

                    foreach ($employesDeLaSociete as $ajout) {
                        if ((null === $ajout->getDateSortieEmployer() || new \DateTime() < $ajout->getDateSortieEmployer())) {
                            // Ajouter à la liste des employés à considérer
                            // Assurez-vous de ne pas ajouter de doublons si un employé travaille pour plusieurs sociétés
                            if (!in_array($ajout, $ajouterTrie)) {
                                $ajouterTrie[] = $ajout;
                            }
                        }
                       
                    }

                    
                }


                
                $joursTravail = [];

                for ($i = 1; $i <= 31; $i++) {

                    $date = (new DateTime())->modify("+$i day");
                    $jourActuelEnFrancais = array_search(strtolower($date->format('l')), array_map('strtolower', $joursCorrespondance), true);

                    foreach ($ajouterTrie as $ajouts) {
                       
                        $societe = $ajouts->getSociete();
                        // $nomSociete = $societe->getNomSociete();
                        // $adresseSociete = $societe->getAdresseSociete();
                        // $codePostalSociete = $societe->getCodePostalSociete();


                        if (in_array($jourActuelEnFrancais, array_map('strtolower', $ajouts->getJoursTravailler()))) {
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


                            $rechercherEmployer = $entityManager->getRepository(User::class)->find($employe->getUser());
                            $nom = $rechercherEmployer->getNom();
                            $prenom = $rechercherEmployer->getPrenom();

                            $disponibilites[(string)$employe->getId()] = [
                                $jour,
                                $heure,
                                $nom,
                                $prenom,
                                // $nomSociete,
                                // $adresseSociete,
                                // $codePostalSociete
                            ];

                            $trouve = true;
                            break;
                        }
                    }
                    if ($trouve) break;
                }
            }
        }
    

        return $this->render('rdv/findrdv.html.twig', [
            'controller_name' => 'RdvController',
            'disponibilites' => $disponibilites,
            'employes' => $employes,
            'patient' => $patient,
            'nom' => $nom,
            'prenom' => $prenom,
            'form' => $form->createView(),

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
