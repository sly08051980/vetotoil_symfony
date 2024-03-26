<?php

namespace App\Controller;

use App\Entity\Ajouter;
use App\Entity\Employer;
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
}
