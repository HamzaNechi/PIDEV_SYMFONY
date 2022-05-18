<?php

namespace App\Controller;

use App\Entity\Saisons;
use App\Form\SaisonsType;
use App\Repository\SaisonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Saison;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/saisons")
 */
class SaisonsController extends AbstractController
{
    /**
     * @Route("/", name="app_saisons_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $saisons = $entityManager
            ->getRepository(Saisons::class)
            ->findAll();

        return $this->render('saisons/index.html.twig', [
            'saisons' => $saisons,
        ]);
    }

    /**
     * @Route("/new", name="app_saisons_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $saison = new Saisons();
        $form = $this->createForm(SaisonsType::class, $saison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($saison);
            $entityManager->flush();

            return $this->redirectToRoute('app_saisons_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('saisons/new.html.twig', [
            'saison' => $saison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{saisonId}", name="app_saisons_show", methods={"GET"})
     */
    public function show(SaisonRepository $repo,$saisonId): Response
    {
        return $this->render('saisons/show.html.twig', [
            'saison' => $repo->find($saisonId),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_saisons_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request,EntityManagerInterface $entityManager,$id): Response
    {
        $saison=$this->getDoctrine()->getRepository(Saisons::class)->find($id);
        $form = $this->createForm(SaisonsType::class, $saison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_saisons_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('saisons/edit.html.twig', [
            'saison' => $saison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{saisonId}", name="app_saisons_delete", methods={"POST"})
     */
    public function delete(Request $request,$saisonId, SaisonRepository $repo, EntityManagerInterface $entityManager): Response
    {
        $saison=$repo->find($saisonId);
        if ($this->isCsrfTokenValid('delete'.$saison->getId(), $request->request->get('_token'))) {
            $entityManager->remove($saison);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_saisons_index', [], Response::HTTP_SEE_OTHER);
    }
}
