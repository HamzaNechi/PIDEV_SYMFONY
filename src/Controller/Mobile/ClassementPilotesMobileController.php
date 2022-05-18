<?php

namespace App\Controller\Mobile;

use App\Entity\ClassementPilotes;
use App\Entity\Pilotes;
use App\Repository\ClassementPilotesRepository;
use App\Repository\SaisonRepository;
use App\Repository\PilotesRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/mobile/classementPilotes")
 */
class ClassementPilotesMobileController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(ClassementPilotesRepository $classementPilotesRepository): Response
    {
        $classementPilotess = $classementPilotesRepository->findAll();

        $locatioznJson = [];
        if ($classementPilotess) {
            foreach ($classementPilotess as $location) {
                $locationJson = $location->jsonSerialize();
                $locationJson["pilotes"] = $this->getDoctrine()->getRepository(Pilotes::class)->find($location->getPilotesPiloteId());
                $locatioznJson[] = $locationJson;
            }
            return new JsonResponse($locatioznJson, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request, SaisonRepository $saisonsRepository, PilotesRepository $pilotesRepository): JsonResponse
    {
        $classementPilotes = new ClassementPilotes();

        return $this->manage($classementPilotes, $saisonsRepository, $pilotesRepository, $request, false);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, ClassementPilotesRepository $classementPilotesRepository, SaisonRepository $saisonsRepository, PilotesRepository $pilotesRepository): Response
    {
        $classementPilotes = $classementPilotesRepository->find((int)$request->get("id"));

        if (!$classementPilotes) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($classementPilotes, $saisonsRepository, $pilotesRepository, $request, true);
    }

    public function manage($classementPilotes, $saisonsRepository, $pilotesRepository, $request, $isEdit): JsonResponse
    {
        $saisons = $saisonsRepository->find((int)$request->get("saisons"));
        if (!$saisons) {
            return new JsonResponse("saisons with id " . (int)$request->get("saisons") . " does not exist", 203);
        }

        $pilotes = $pilotesRepository->find((int)$request->get("pilotes"));
        if (!$pilotes) {
            return new JsonResponse("pilotes with id " . (int)$request->get("pilotes") . " does not exist", 203);
        }


        $classementPilotes->setUp(
            $saisons,
            $pilotes,
            (int)$request->get("pointsTotal"),
            (int)$request->get("position")
        );


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($classementPilotes);
        $entityManager->flush();

        return new JsonResponse($classementPilotes, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, ClassementPilotesRepository $classementPilotesRepository): JsonResponse
    {
        $classementPilotes = $classementPilotesRepository->find((int)$request->get("id"));

        if (!$classementPilotes) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($classementPilotes);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, ClassementPilotesRepository $classementPilotesRepository): Response
    {
        $classementPilotess = $classementPilotesRepository->findAll();

        foreach ($classementPilotess as $classementPilotes) {
            $entityManager->remove($classementPilotes);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }

}
