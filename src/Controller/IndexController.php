<?php

namespace App\Controller;

use App\Entity\Calender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Equipes;
use App\Entity\Membres;
use App\Entity\Pilotes;
use App\Repository\MembresRepository;
use App\Repository\CalenderRepository;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="app_index")
     */
    public function index(): Response
    {   $repoPilote=$this->getDoctrine()->getRepository(Pilotes::class);
        $nbrpilotes=$repoPilote->NombrePilote();
        $repo=$this->getDoctrine()->getRepository(Equipes::class);
        $equipe=$repo->findAll();
        $nbrequipe=$repo->NombreEquipe();
        return $this->render('index/index.html.twig',[
            'equipes'=>$equipe,
            'nbrpilotes'=>$nbrpilotes,
            'nbrequipe'=>$nbrequipe,
            'data'=> $this->getCalendar(),
        ]);
    }


    public function getCalendar(){
        $events = $this->getDoctrine()->getRepository(Calender::class)->findAll();

        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'title' => $event->getTitle(),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
            ];
        }

        return $data = json_encode($rdvs);
    }

    /**
     * @Route("/equipe_vitrine", name="equipe_vitrine")
     */
    public function equipe_vitrine(): Response
    {
        $repo=$this->getDoctrine()->getRepository(Equipes::class);
        $equipe=$repo->findAll();
        return $this->render('equipe/index_vitrine.html.twig',[
            'equipes'=>$equipe
        ]);
    }

    /**
     * @Route("/membre_vitrine/{id}",name="membre_vitrine")
     */
    public function membre_vitrine(MembresRepository $repo,$id): Response
    {
        $membres=$repo->findBy(array('equipe'=>$id));
        return $this->render('membre/index_vitrine.html.twig',[
            'membres'=>$membres
        ]);
    }
}
