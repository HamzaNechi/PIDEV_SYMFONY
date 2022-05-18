<?php
namespace App\Controller\Mobile;

use App\Entity\Pilotes;
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
 * @Route("/mobile/pilotes")
 */
class PilotesMobileController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     */
    public function index(PilotesRepository $pilotesRepository): Response
    {
        $pilotess = $pilotesRepository->findAll();

        if ($pilotess) {
            return new JsonResponse($pilotess, 200);
        } else {
            return new JsonResponse([], 204);
        }
    }

    /**
     * @Route("/add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $pilotes = new Pilotes();

        return $this->manage($pilotes, $request, false);
    }

    /**
     * @Route("/edit", methods={"POST"})
     */
    public function edit(Request $request, PilotesRepository $pilotesRepository): Response
    {
        $pilotes = $pilotesRepository->find((int)$request->get("id"));

        if (!$pilotes) {
            return new JsonResponse(null, 404);
        }

        return $this->manage($pilotes, $request, true);
    }

    public function manage($pilotes, $request, $isEdit): JsonResponse
    {   
        
        $pilotes->setUp(
            (int)$request->get("numero")
        );
        
        

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($pilotes);
        $entityManager->flush();

        return new JsonResponse($pilotes, 200);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request, EntityManagerInterface $entityManager, PilotesRepository $pilotesRepository): JsonResponse
    {
        $pilotes = $pilotesRepository->find((int)$request->get("id"));

        if (!$pilotes) {
            return new JsonResponse(null, 200);
        }

        $entityManager->remove($pilotes);
        $entityManager->flush();

        return new JsonResponse([], 200);
    }

    /**
     * @Route("/deleteAll", methods={"POST"})
     */
    public function deleteAll(EntityManagerInterface $entityManager, PilotesRepository $pilotesRepository): Response
    {
        $pilotess = $pilotesRepository->findAll();

        foreach ($pilotess as $pilotes) {
            $entityManager->remove($pilotes);
            $entityManager->flush();
        }

        return new JsonResponse([], 200);
    }
    
}
