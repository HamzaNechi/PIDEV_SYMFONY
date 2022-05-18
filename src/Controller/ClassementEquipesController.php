<?php

namespace App\Controller;

use App\Entity\ClassementEquipes;
use App\Form\ClassementEquipesType;
use App\Repository\ClassementEquipesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/classement/equipes")
 */
class ClassementEquipesController extends AbstractController
{
    /**
     * @Route("/", name="app_classement_equipes_index", methods={"GET"})
     */
    public function index(ClassementEquipesRepository $classementEquipesRepository): Response
    {
        return $this->render('classement_equipes/index.html.twig', [
            'classement_equipes' => $classementEquipesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_classement_equipes_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ClassementEquipesRepository $classementEquipesRepository): Response
    {
        $classementEquipe = new ClassementEquipes();
        $form = $this->createForm(ClassementEquipesType::class, $classementEquipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classementEquipesRepository->add($classementEquipe);
            return $this->redirectToRoute('app_classement_equipes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('classement_equipes/new.html.twig', [
            'classement_equipe' => $classementEquipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{classementEId}", name="app_classement_equipes_show", methods={"GET"})
     */
    public function show(ClassementEquipesRepository $repo,$classementEId): Response
    {
        $classementEquipe=$repo->find($classementEId);
        return $this->render('classement_equipes/show.html.twig', [
            'classement_equipe' => $classementEquipe,
        ]);
    }

    /**
     * @Route("/{classementEId}/edit", name="app_classement_equipes_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, $classementEId, ClassementEquipesRepository $classementEquipesRepository): Response
    {
        $classementEquipe=$classementEquipesRepository->find($classementEId);
        $form = $this->createForm(ClassementEquipesType::class, $classementEquipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classementEquipesRepository->add($classementEquipe);
            return $this->redirectToRoute('app_classement_equipes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('classement_equipes/edit.html.twig', [
            'classement_equipe' => $classementEquipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{classementEId}", name="app_classement_equipes_delete", methods={"POST"})
     */
    public function delete(Request $request,ClassementEquipesRepository $classementEquipesRepository,$classementEId): Response
    {
        $classementEquipe=$classementEquipesRepository->find($classementEId);
        if ($this->isCsrfTokenValid('delete'.$classementEquipe->getId(), $request->request->get('_token'))) {
            $classementEquipesRepository->remove($classementEquipe);
        }

        return $this->redirectToRoute('app_classement_equipes_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @Route("/indexf", name="app_classement_equipes_index1", methods={"GET"})
     */
    public function indexf(ClassementEquipesRepository $classementEquipesRepository): Response
    {
        return $this->render('classement_equipes/ClassementEquipeF.html.twig', [
            'classement_equipes' => $classementEquipesRepository->findAll(),
        ]);
    }
}
