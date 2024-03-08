<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PatientController extends AbstractController
{
    #[Route('/patient/profil', name: 'app_patient_profil')]
    public function update(EntityManagerInterface $entityManager,Security $security): Response
    {

        $user = $security->getUser();
        $userId=$user->getId();
  
        if($userId){
       
            $edit = $entityManager->getRepository(Patient::class)->findOneBy(['user' => $userId]);
         
            $form = $this->createForm(PatientType::class,$edit);
            if(!$edit){
                throw $this->createNotFoundException(
                    'Personne de trouver '
                );
            }
  

            return $this->render('patient/index.html.twig',[
                'form' => $form->createView(),
            ]);

        }



        return $this->render('patient/index.html.twig', [
            'controller_name' => 'PatientController',
        ]);
    }
}
