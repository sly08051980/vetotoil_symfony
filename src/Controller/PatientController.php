<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PatientController extends AbstractController
{
    #[Route('/patient/profil', name: 'app_patient_profil')]
    public function update(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        $userId = $user->getId();
    
        $edit = $entityManager->getRepository(Patient::class)->findOneBy(['user' => $userId]);
    
        if (!$edit) {
            throw $this->createNotFoundException('Personne trouvée.');
        }
    
        $form = $this->createForm(PatientType::class, $edit);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
    
            return $this->redirectToRoute('app_home');
        }
    
        return $this->render('patient/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
