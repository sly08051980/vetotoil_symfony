<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalEditType;
use App\Form\AnimalType;
use App\Repository\AnimalRepository;
use App\Repository\PatientRepository;
use App\Repository\RaceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AnimalController extends AbstractController
{

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/animal/ajouter', name: 'app_animal_ajouter')]
    public function index(Request $request, EntityManagerInterface $entityManager, Security $security,PatientRepository $patientRepository): Response
    {
        $patientId =Null;
        $animal = new Animal();
        $animal->setDateCreationAnimal(new \DateTime());
        
        $user = $this->security->getUser();
       
        $patient = $patientRepository->findPatientByUser($user);
        if ($patient !== null) {
           
            $patientId = $patient->getId();
         
            
            $animal->setPatient($patient);
        }
       
        $form = $this->createForm(AnimalType::class, $animal, [
            'user' => $user,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($animal);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('animal/ajouteranimal.html.twig', [
            'controller_name' => 'AnimalController',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/get-races', name: 'get_races')]
    public function getRaces(Request $request, RaceRepository $raceRepository): JsonResponse
    {
        $typeId = $request->query->get('typeId');
        $races = $raceRepository->findBy(['type' => $typeId]);

        $data = [];
        foreach ($races as $race) {
            $data[] = ['id' => $race->getId(), 'name' => $race->getRaceAnimal()];
        }

        return new JsonResponse($data);
    }

    #[Route('/animal/find', name: 'app_animal_find')]
    public function find(Request $request, Security $security, AnimalRepository $animalRepository): Response
    {

        $user = $security->getUser();

        if (!$user) {

            return $this->redirectToRoute('app_login');
        }


        $animaux = $animalRepository->findByUser($user);

        return $this->render('animal/findanimal.html.twig', [
            'animaux' => $animaux,
        ]);
    }

    #[Route('/animal/edit/{id}', name: 'app_animal_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Animal $animal, Security $security): Response
    {
        $user = $security->getUser();

        $form = $this->createForm(AnimalEditType::class, $animal, [
            'user' => $user,

        ]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($animal);
            $entityManager->flush();

            return $this->redirectToRoute('app_animal_find');
        }

        return $this->render('animal/editanimal.html.twig', [
            'form' => $form->createView(),
            'animal' => $animal,
        ]);
    }
}
