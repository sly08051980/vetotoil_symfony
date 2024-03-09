<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Repository\PatientRepository;
use App\Repository\RaceRepository;
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

    #[Route('/animal', name: 'app_animal')]
    public function index(Request $request,EntityManagerInterface $entityManager,Security $security,PatientRepository $patientRepository): Response
    {
$animal = new Animal();
 $animal->setDateCreationAnimal(new \DateTime());
 
        $form = $this->createForm(AnimalType::class,$animal);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->security->getUser();
            $patient = $user->getId();
            if ($patient) {
                $animal->setPatient($patient); 
            }
            $entityManager->persist($animal);
            $entityManager->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('animal/ajouteranimal.html.twig', [
            'controller_name' => 'AnimalController',
            'form'=>$form->createView(),
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
}
