<?php

namespace App\Controller;

use App\Entity\Rdv;
use Carbon\Carbon;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RdvController extends AbstractController
{
    #[Route('/rdv', name: 'app_rdv')]
    public function index(EntityManagerInterface $entityManager): Response
    {
      
        $tomorrow = Carbon::now()->addDay();
       $tomorrow->locale('fr')->isoFormat('dddd, MMMM Do YYYY, h:mm');
       dump($tomorrow);
       die;
       
        $events = $entityManager->getRepository(Rdv::class)->findAll();
        return $this->render('rdv/index.html.twig', [
            'controller_name' => 'RdvController',
            'events'=>$events,

        
        ]);
    }
}
