<?php

namespace App\Controller;

use App\Entity\Employer;
use App\Entity\Patient;
use App\Entity\Rdv;
use App\Entity\Societe;
use App\Entity\User;
use App\Form\EmployerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EmployerController extends AbstractController
{

    #[Route('employer/{id}/edit', name: 'app_edit_employer', methods: ['GET', 'POST'])]

    public function editEmployer(Request $request, EntityManagerInterface $entityManager, Security $security, $id): Response
    {
        $user = $security->getUser();
        $userId = $user->getId();
        $edit = $entityManager->getRepository(Employer::class)->findOneBy(['user' => $userId]);
        $fiche = $entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);


        $form = $this->createForm(EmployerType::class, $edit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('employer/index.html.twig', [
            'controller_name' => 'EmployerController',
            'form' => $form,
            'fiche' => $fiche,
        ]);
    }
    #[Route('/rdv/employer/ajouter', name: 'app_rdv_employer_ajouter')]

    public function ajouterRdvParEmployer(Request $request, EntityManagerInterface $entityManager, Security $security)
    {
        $ajouter = $request->request->get('ajouterRdv');
        $ajouterRdv = email_form($ajouter);
        $heure = $request->request->get('heure');
        $date = $request->request->get('date');

        $dateRdv = new \DateTime($date);
$heureRdv = \DateTime::createFromFormat('H:i', $heure); 


      
        $rechercher = $entityManager->getRepository(User::class)->findOneBy(['email' => $ajouterRdv]);
        $patient = $entityManager->getRepository(Patient::class)->findOneBy(['user' => $rechercher]);
        $user = $security->getUser();
        $userId = $user->getId();
        $employer = $entityManager->getRepository(User::class)->findOneBy(['id' => $userId]);
        $societe=$entityManager->getRepository(Societe::class)->findBy(['']);

       $rdv=new Rdv();
       $rdv->setDateRdv($dateRdv);
       $rdv->setHeureRdv($heureRdv);
       $rdv->setPatient($patient);
       $rdv->setEmployer($employer);
       $rdv->setSociete($societe);

        dd($rdv);
    }
}
