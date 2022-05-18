<?php

namespace App\Controller;

use App\Entity\Qualifying;
use App\Entity\Participation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\QualifyingType;

class QualifyingController extends AbstractController
{

    /**
     * @Route("/qualifying", name="qualifying")
     */
    public function index(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Qualifying::class);
        $qualifyings = $repo->findAll();
        return $this->render('qualifying/index.html.twig', [
            'controller_name' => 'QualifyingController',
            'qualifyings' => $qualifyings
        ]);
    }





    /**
     * @Route("/addQualifying/{idp}", name="addQualifying")
     */

    public function addQualifying(Request $request, $idp): Response
    {
        $qualifying = new Qualifying();
        $form = $this->createForm(QualifyingType::class, $qualifying);
        $form->add('Ajouter', SubmitType::class);
        $form->add('Annuler', ResetType::class, [
            'attr' => [
                'class' => 'btn btn-secondary'
            ]
        ]);
        $part = $this->getDoctrine()->getRepository(Participation::class)->find($idp);
        $qualifying->setPilote($part->getPilote());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qualifying);
            $em->flush();

            $part->setQualifying($qualifying);
            $part->setGrid($qualifying->getPosition());
            $em->flush();
            return $this->redirectToRoute('participation');
        }
        return $this->render(
            'qualifying/ajouterQualifying.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/addQualOrg/{idp}", name="addQualOrg")
     */

    public function addQualOrg(Request $request, $idp): Response
    {
        $qualifying = new Qualifying();
        $form = $this->createForm(QualifyingType::class, $qualifying);
        $form->add('Ajouter', SubmitType::class);
        $form->add('Annuler', ResetType::class, [
            'attr' => [
                'class' => 'btn btn-secondary'
            ]
        ]);
        $part = $this->getDoctrine()->getRepository(Participation::class)->find($idp);
        $qualifying->setPilote($part->getPilote());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($qualifying);
            $em->flush();

            $part->setQualifsying($qualifying);
            $part->setGrid($qualifying->getPosition());
            $em->flush();
            return $this->redirectToRoute('courseParticipation', ['course' => $part->getCourse()->getId()]);
        }
        return $this->render(
            'qualifying/ajouterQualifying.html.twig',
            ['form' => $form->createView()]
        );
    }





    /**
     * @Route("/editQualifying/{idq}", name="editQualifying")
     */
    public function editQualifying(Request $request, $idq): Response
    {
        $repo = $this->getDoctrine()->getRepository(Qualifying::class);
        $qualifying = $repo->find($idq);

        $form = $this->createForm(QualifyingType::class, $qualifying);
        $form->add('Modifier', SubmitType::class);
        $form->add('Annuler', ResetType::class, [
            'attr' => [
                'class' => 'btn btn-secondary'
            ]
        ]);
        $part = $this->getDoctrine()
            ->getRepository(Participation::class)
            ->findOneBy(['qualifying' => $qualifying]);
        $qualifying->setPilote($part->getPilote());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $part->setGrid($qualifying->getPosition());

            $em->flush();

            return $this->redirectToRoute('participation');
        }
        return $this->render(
            'qualifying/editQualifying.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/editQualOrg/{idq}", name="editQualOrg")
     */
    public function editQualOrg(Request $request, $idq): Response
    {
        $repo = $this->getDoctrine()->getRepository(Qualifying::class);
        $qualifying = $repo->find($idq);
        $part = $this->getDoctrine()->getRepository(Participation::class)->findBy(['qualifying'=>$idq]);

        $form = $this->createForm(QualifyingType::class, $qualifying);
        $form->add('Modifier', SubmitType::class);
        $form->add('Annuler', ResetType::class, [
            'attr' => [
                'class' => 'btn btn-secondary'
            ]
        ]);
        $part = $this->getDoctrine()
            ->getRepository(Participation::class)
            ->findOneBy(['qualifying' => $qualifying]);
        $qualifying->setPilote($part->getPilote());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $part->setGrid($qualifying->getPosition());

            $em->flush();

            return $this->redirectToRoute('courseParticipation', ['course' => $part->getCourse()->getId()]);
        }
        return $this->render(
            'qualifying/editQualifying.html.twig',
            ['form' => $form->createView()]
        );
    }





    /**
     * @Route("/deleteQualifying/{id}" , name="deleteQualifying")
     */
    public function deleteQualifying($id)
    {
        $repo = $this->getDoctrine()->getRepository(Qualifying::class);
        $qualifying = $repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($qualifying);
        $em->flush();
        return $this->redirectToRoute('qualifying');
    }




    /**
     * @Route("/qualifyings", name="qualifyings")
     */
    public function vit(): Response
    {
        $repo = $this->getDoctrine()->getRepository(Qualifying::class);
        $qualifyings = $repo->findAll();
        return $this->render('qualifying/index_vitrine.html.twig', [
            'controller_name' => 'QualifyingController',
            'qualifyings' => $qualifyings
        ]);
    }
}
