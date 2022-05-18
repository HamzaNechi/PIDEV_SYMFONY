<?php

namespace App\Controller;

use App\Entity\Calender;
use App\Form\CalenderType;
use App\Repository\CalenderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/calender")
 */
class CalenderController extends AbstractController
{
    /**
     * @Route("/", name="app_calender_index", methods={"GET"})
     */
    public function index(CalenderRepository $calenderRepository): Response
    {
        return $this->render('calender/index.html.twig', [
            'calenders' => $calenderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_calender_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CalenderRepository $calenderRepository): Response
    {
        $calender = new Calender();
        $form = $this->createForm(CalenderType::class, $calender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calenderRepository->add($calender);
            return $this->redirectToRoute('app_courses_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('calender/new.html.twig', [
            'calender' => $calender,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_calender_show", methods={"GET"})
     */
    public function show(Calender $calender): Response
    {
        return $this->render('calender/show.html.twig', [
            'calender' => $calender,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_calender_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Calender $calender, CalenderRepository $calenderRepository): Response
    {
        $form = $this->createForm(CalenderType::class, $calender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $calenderRepository->add($calender);
            return $this->redirectToRoute('app_calender_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('calender/edit.html.twig', [
            'calender' => $calender,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_calender_delete", methods={"POST"})
     */
    public function delete(Request $request, Calender $calender, CalenderRepository $calenderRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$calender->getId(), $request->request->get('_token'))) {
            $calenderRepository->remove($calender);
        }

        return $this->redirectToRoute('app_calender_index', [], Response::HTTP_SEE_OTHER);
    }
}
