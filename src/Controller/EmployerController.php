<?php

namespace App\Controller;

use App\Entity\Employer;
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

        public function editEmployer(Request $request,EntityManagerInterface $entityManager,Security $security,$id): Response
        {
       $user=$security->getUser();
       $userId=$user->getId();
    $edit=$entityManager->getRepository(Employer::class)->findOneBy(['user'=>$userId]);
$fiche=$entityManager->getRepository(User::class)->findOneBy(['id'=>$userId]);

     
        $form=$this->createForm(EmployerType::class,$edit);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }
        
           return $this->render('employer/index.html.twig', [
            'controller_name' => 'EmployerController',
            'form'=>$form,
            'fiche'=>$fiche,
        ]);
   
    
        }
       
    
}
