<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Entity\Rdv;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
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
        // Supposez que vous avez une relation ou un moyen de récupérer l'Employer à partir de User
        $employer = $entityManager->getRepository(Employer::class)->findOneBy(['user' => $user]);
        
        if ($employer) {
            $rdvs = $entityManager->getRepository(Rdv::class)->findBy(['employer' => $employer]);
            $filteredRdvs = [];
        
            $today = new \DateTime(); // Date d'aujourd'hui
            $inSevenDays = (clone $today)->modify('+7 days'); // Date dans 7 jours
        
            foreach ($rdvs as $rdv) {
                // Vérifiez si le status_rdv est null et si la date_rdv est entre aujourd'hui et dans 7 jours
                if ($rdv->getStatusRdv() === null &&
                    $rdv->getDateRdv() >= $today &&
                    $rdv->getDateRdv() <= $inSevenDays) {
                    $filteredRdvs[] = $rdv;
                }
            }
        
            // Utiliser $filteredRdvs pour les opérations suivantes
            dd($filteredRdvs);
        }


        return $this->render('rdv_employer/index.html.twig', [
            'controller_name' => 'RdvEmployerController',
        ]);
    }
}
