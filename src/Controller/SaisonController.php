<?php

namespace App\Controller;

use App\Entity\Saison;
use App\Form\SaisonType;
use App\Repository\SaisonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/saison")
 */
class SaisonController extends AbstractController
{
    /**
     * @Route("/", name="app_saison_index", methods={"GET"})
     */
    public function index(SaisonRepository $saisonRepository): Response
    {
        return $this->render('saison/index.html.twig', [
            'saisons' => $saisonRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_saison_new", methods={"GET", "POST"})
     */
    public function new(Request $request, SaisonRepository $saisonRepository): Response
    {
        $saison = new Saison();
        $form = $this->createForm(SaisonType::class, $saison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saisonRepository->add($saison);
            return $this->redirectToRoute('app_saison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('saison/new.html.twig', [
            'saison' => $saison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_saison_show", methods={"GET"})
     */
    public function show(Saison $saison): Response
    {
        return $this->render('saison/show.html.twig', [
            'saison' => $saison,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_saison_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Saison $saison, SaisonRepository $saisonRepository): Response
    {
        $form = $this->createForm(SaisonType::class, $saison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saisonRepository->add($saison);
            return $this->redirectToRoute('app_saison_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('saison/edit.html.twig', [
            'saison' => $saison,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_saison_delete", methods={"POST"})
     */
    public function delete(Request $request, Saison $saison, SaisonRepository $saisonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$saison->getId(), $request->request->get('_token'))) {
            $saisonRepository->remove($saison);
        }

        return $this->redirectToRoute('app_saison_index', [], Response::HTTP_SEE_OTHER);
    }
}
